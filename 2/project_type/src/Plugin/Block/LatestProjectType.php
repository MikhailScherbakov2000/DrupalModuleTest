<?php

namespace Drupal\projects_type\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;

/**
 * @Block(
 *   id = "latest_projects_block",
 *   admin_label = @Translation("3 последних проекта"),
 * )
 */
class LatestProjectsBlock extends BlockBase {

  public function build() {
    $query = \Drupal::entityQuery('node')
      ->condition('status', 1)
      ->condition('type', 'project')
      ->sort('created', 'DESC')
      ->range(0, 3);
    $nids = $query->execute();

    $nodes = Node::loadMultiple($nids);

    $items = [];
    foreach ($nodes as $node) {
      $items[] = [
        'title' => $node->label(),
        'url' => $node->toUrl()->toString(),
        'date' => $node->get('field_end_date')->value,
        'image' => '',
      ];

        if (!$node->get('field_project_image')->isEmpty()) {
        $file = $node->get('field_project_image')->entity;
        if ($file) {
          $uri = $file->getFileUri();
          $url = \Drupal\image\Entity\ImageStyle::load('thumbnail')->buildUrl($uri);
          $items[count($items)-1]['image'] = $url;
        }
      }
    }

    $render = [
      '#theme' => 'item_list',
      '#items' => [],
      '#title' => $this->t('Последние проекты'),
    ];

    foreach ($items as $item) {
      $render['#items'][] = [
        '#markup' => '<a href="' . $item['url'] . '">' . $item['title'] . '</a> — ' . $item['date'] .
          ($item['image'] ? '<br><img src="' . $item['image'] . '" alt="' . $item['title'] . '">' : ''),
      ];
    }

    return $render;
  }
}
