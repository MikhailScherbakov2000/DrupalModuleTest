<?php

namespace Drupal\projects_type\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\node\Entity\Node;
use Drupal\image\Entity\ImageStyle;

class ProjectsJsonController extends ControllerBase {

  public function latestJson() {
    $query = \Drupal::entityQuery('node')
      ->condition('status', 1)
      ->condition('type', 'project')
      ->sort('created', 'DESC')
      ->range(0, 3);
    $nids = $query->execute();

    $nodes = Node::loadMultiple($nids);

    $items = [];
    foreach ($nodes as $node) {
      $image_url = '';
      if (!$node->get('field_project_image')->isEmpty()) {
        $file = $node->get('field_project_image')->entity;
        if ($file) {
          $uri = $file->getFileUri();
          $image_url = ImageStyle::load('thumbnail')->buildUrl($uri);
        }
      }
      $items[] = [
        'title' => $node->label(),
        'url' => $node->toUrl()->toString(),
        'date' => $node->get('field_end_date')->value,
        'image' => $image_url,
      ];
    }

    return new JsonResponse($items);
  }
}
