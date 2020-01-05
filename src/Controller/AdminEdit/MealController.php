<?php

namespace App\Controller\AdminEdit;

use App\Entity\Meal;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MealController extends AbstractController
{

    /**
     * @Route("/admin/meal/{id}/edit", name="admin_edit_meal")
     */
    public function editMeal(Meal $meal)
    {
        return $this->render('admin/edit/meal.html.twig', [
            'meal' => $meal,
        ]);
    }

    /**
     * @Route("/admin/meal/new", name="admin_new_meal")
     */
    public function newMeal()
    {
        return $this->render('admin/edit/newMeal.html.twig');
    }

    /**
     * @Route("/admin/deleteMeal", name="admin_delete_meal")
     * @param Request $request
     * @param Filesystem $filesystem
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function deleteMeal(Request $request, Filesystem $filesystem, ValidatorInterface $validator): Response
    {
        if ($this->isCsrfTokenValid('delete-meal', $request->get('csrfData')) && !empty($request->get('id'))) {
            $sql = $this->getDoctrine()->getRepository(Meal::class)->find($request->get('id'));
            if (count($validator->validate($sql)) == 0) {
                $filesystem->remove(['symlink', "../public/" . $sql->getImage(), 'activity.log']);
                $this->getDoctrine()->getRepository(Meal::class)->deleteMeal($sql);
                return $this->json(['message' => "Vous avez bien supprimer ce repas", 'id' => $request->get('id')], 200);
            }
        }
        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
    }

    /**
     * @Route("/admin/saveMeal", name="admin_save_meal")
     * @param Security $security
     * @param Request $request
     * @param Filesystem $filesystem
     * @param ValidatorInterface $validator
     * @return Response
     * @throws \Exception
     */
    public function saveMeal(Security $security, Request $request, Filesystem $filesystem, ValidatorInterface $validator): Response
    {
        if ($this->isCsrfTokenValid('save-item', $request->get('csrfData')) && !empty($request->get('name')) && !empty($request->get('meal_id')) && !empty($request->get('recipe'))) {
            $image = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
            if (!empty($request->get('image'))) {
                $image = $request->get('image');
            }
            if ($request->get('meal_id') == "new") {
                $meal = new Meal();
                $meal->setName($request->get('name'))
                    ->setImage($image)
                    ->setRecipe($request->get('recipe'))
                    ->setPostedBy($security->getUser())
                    ->setCreatedAt(new \DateTime());
                if (count($validator->validate($meal)) == 0) {
                    $meal = $this->getDoctrine()->getRepository(Meal::class)->createMeal($meal);
                    return $this->json(['message' => "Le repas à bien été créer !", 'mealId' => $meal], 200);
                }
            } else {
                $meal = $this->getDoctrine()->getRepository(Meal::class)->find($request->get('meal_id'));
                $oldImage = $meal->getImage();
                $meal->setName($request->get('name'))
                    ->setImage($image)
                    ->setRecipe($request->get('recipe'))
                    ->setPostedBy($security->getUser());
                if (count($validator->validate($meal)) == 0) {
                    if (substr($oldImage, 0, 4) !== "http" && $request->get('image') !== $oldImage) {
                        $filesystem->remove(['symlink', "../public/" . $oldImage, 'activity.log']);
                    }
                    $meal = $this->getDoctrine()->getRepository(Meal::class)->saveMeal($meal);
                    return $this->json(['message' => "Le repas à bien été mis à jour !", 'mealId' => $meal->getId()], 200);
                }
            }
            return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
        }
        return $this->json(['message' => 'Veuillez remplir tout les champs !'], 400);
    }
}
