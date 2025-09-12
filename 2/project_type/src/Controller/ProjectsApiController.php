<?php

namespace Drupal\projects_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

class ProjectsApiController extends ControllerBase {

  public function getProjects() {
    $query = \Drupal::entityQuery('node')
      ->condition('status', 1)
      ->condition('type', 'project')
      ->sort('created', 'DESC')
      ->range(0, 10);
    $nids = $query->execute();

    $nodes = Node::loadMultiple($nids);
    $projects = [];

    foreach ($nodes as $node) {
      $projects[] = [
        'title' => $node->label(),
        'description' => $node->get('body')->value,
        'image_url' => '',
        'end_date' => $node->get('field_end_date')->value,
      ];

      if (!$node->get('field_project_image')->isEmpty()) {
        $file = $node->get('field_project_image')->entity;
        if ($file) {
          $uri = $file->getFileUri();
          $projects[count($projects)-1]['image_url'] = file_create_url($uri);
        }
      }
    }

    return [
      '#type' => 'json',
      '#cache' => ['max-age' => 0],
      'projects' => $projects,
    ];
  }

}
