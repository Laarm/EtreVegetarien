<?php

namespace App\Controller\AdminEdit;

use App\Entity\Store;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StoreController extends AbstractController
{

    /**
     * @Route("/admin/store/{id}/edit", name="admin_edit_store")
     * @param Store $store
     * @return Response
     */
    public function editStore(Store $store)
    {
        return $this->render('admin/edit/store.html.twig', [
            'store' => $store,
        ]);
    }

    /**
     * @Route("/admin/store/new", name="admin_new_store")
     */
    public function newStore()
    {
        return $this->render('admin/edit/newStore.html.twig');
    }

    /**
     * @Route("/admin/deleteStore", name="admin_delete_store")
     * @param Request $request
     * @param Filesystem $filesystem
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function deleteStore(Request $request, Filesystem $filesystem, ValidatorInterface $validator): Response
    {
        if ($this->isCsrfTokenValid('delete-store', $request->get('csrfData')) && !empty($request->get('id'))) {
            $sqlStore = $this->getDoctrine()->getRepository(Store::class)->find($request->get('id'));
            if (count($validator->validate($sqlStore)) == 0) {
                $filesystem->remove(['symlink', "../public/" . $sqlStore->getImage(), 'activity.log']);
                $this->getDoctrine()->getRepository(Store::class)->deleteStore($sqlStore);
                return $this->json(['message' => "Vous avez bien supprimer ce store", 'id' => $request->get('id')], 200);
            }
        }
        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
    }

    /**
     * @Route("/admin/saveStore", name="admin_save_store")
     * @param Request $request
     * @param Filesystem $filesystem
     * @param ValidatorInterface $validator
     * @return Response
     * @throws \Exception
     */
    public function saveStore(Request $request, Filesystem $filesystem, ValidatorInterface $validator): Response
    {
        if ($this->isCsrfTokenValid('save-item', $request->get('csrfData')) && !empty($request->get('name')) && !empty($request->get('city')) && !empty($request->get('store_id'))) {
            $image = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
            if (!empty($request->get('image'))) {
                $image = $request->get('image');
            }
            if ($request->get('store_id') == "new") {
                $sqlStore = new Store();
                $sqlStore->setName($request->get('name'))
                    ->setImage($image)
                    ->setLocation("null")
                    ->setAddress($request->get('address'))
                    ->setCity($request->get('city'))
                    ->setCreatedAt(new \DateTime());
                if (count($validator->validate($sqlStore)) == 0) {
                    $storeId = $this->getDoctrine()->getRepository(Store::class)->createStore($sqlStore);
                    return $this->json(['message' => "Le magasin à bien été créer !", 'storeId' => $storeId], 200);
                }
            } else {
                $sqlStore = $this->getDoctrine()->getRepository(Store::class)->find($request->get('store_id'));
                $oldImage = $sqlStore->getImage();
                $sqlStore->setName($request->get('name'))
                    ->setImage($image)
                    ->setAddress($request->get('address'))
                    ->setCity($request->get('city'));
                if (count($validator->validate($sqlStore)) == 0) {
                    if (substr($oldImage, 0, 4) !== "http" && $request->get('image') !== $oldImage) {
                        $filesystem->remove(['symlink', "../public/" . $oldImage, 'activity.log']);
                    }
                    $storeId = $this->getDoctrine()->getRepository(Store::class)->saveStore($sqlStore);
                    return $this->json(['message' => "Le magasin à bien été mis à jour !", 'storeId' => $storeId], 200);
                }
            }
            return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
        }
        return $this->json(['message' => 'Veuillez remplir tout les champs !'], 400);
    }
}
