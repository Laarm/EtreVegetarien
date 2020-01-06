<?php

namespace App\Controller\AdminEdit;

use App\Entity\Store;
use Config\Functions;
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
        if ($this->isCsrfTokenValid('save-item', $request->get('csrfData'))) {
            $sqlStore = new Store();
            $sqlStore->setCreatedAt(new \DateTime());
            if ($request->get('store_id') !== "new") {
                $sqlStore = $this->getDoctrine()->getRepository(Store::class)->find($request->get('store_id'));
                $functions = new Functions();
                $functions->deleteFile($request->get('image'), $sqlStore->getImage(), $filesystem);
            }
            $sqlStore->setName($request->get('name'))
                ->setImage($request->get('image'))
                ->setLocation("null")
                ->setAddress($request->get('address'))
                ->setCity($request->get('city'));
            if (count($validator->validate($sqlStore)) == 0) {
                $storeId = $this->getDoctrine()->getRepository(Store::class)->saveStore($sqlStore);
                return $this->json(['message' => "Le magasin à bien été mis à jour !", 'storeId' => $storeId], 200);
            }
        }
        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
    }
}
