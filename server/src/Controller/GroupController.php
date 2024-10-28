<?php

namespace App\Controller;


use App\Entity\Group;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/groups', name: 'api_groups')]
class GroupController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private GroupRepository $groupRepository;

    public function __construct(EntityManagerInterface $entityManager, GroupRepository $groupRepository)
    {
        $this->entityManager = $entityManager;
        $this->groupRepository = $groupRepository;
    }

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $groups = $this->groupRepository->findAll();
        $data = array_map(fn($group) => [
            'id' => $group->getId(),
            'name' => $group->getName(),
        ], $groups);

        return $this->json($data);
    }

    #[Route('/{id}', name: 'view', methods: ['GET'])]
    public function view(int $id): JsonResponse
    {
        $group = $this->groupRepository->find($id);

        if (!$group) {
            return $this->json(['error' => 'Group not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        return $this->json([
            'id' => $group->getId(),
            'name' => $group->getName(),
        ]);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $group = new Group();
        $group->setName($data['name'] ?? '');

        $this->entityManager->persist($group);
        $this->entityManager->flush();

        return $this->json(['message' => 'Group created successfully'], JsonResponse::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        $group = $this->groupRepository->find($id);

        if (!$group) {
            return $this->json(['error' => 'Group not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        $group->setName($data['name'] ?? $group->getName());

        $this->entityManager->flush();

        return $this->json(['message' => 'Group updated successfully']);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $group = $this->groupRepository->find($id);

        if (!$group) {
            return $this->json(['error' => 'Group not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($group);
        $this->entityManager->flush();

        return $this->json(['message' => 'Group deleted successfully']);
    }
}
