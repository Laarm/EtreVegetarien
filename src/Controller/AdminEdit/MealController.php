<?php

namespace App\Controller\AdminEdit;

use App\Entity\Meal;
use Config\Functions;
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
        if ($this->isCsrfTokenValid('save-item', $request->get('csrfData'))) {
            $meal = new Meal();
            $meal->setCreatedAt(new \DateTime());
            if ($request->get('meal_id') !== "new") {
                $meal = $this->getDoctrine()->getRepository(Meal::class)->find($request->get('meal_id'));
                $functions = new Functions();
                $functions->deleteFile($request->get('image'), $meal->getImage(), $filesystem);
            }
            $meal->setName($request->get('name'))
                ->setImage($request->get('image'))
                ->setRecipe($request->get('recipe'))
                ->setPostedBy($security->getUser());
            if (count($validator->validate($meal)) == 0) {
                $meal = $this->getDoctrine()->getRepository(Meal::class)->saveMeal($meal);
                return $this->json(['message' => "Le repas à bien été mis à jour !", 'mealId' => $meal->getId()], 200);
            }
        }
        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
    }
}
