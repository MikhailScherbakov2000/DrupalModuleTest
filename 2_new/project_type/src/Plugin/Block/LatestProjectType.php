<?php
namespace Drupal\projects_type\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;
use Drupal\image\Entity\ImageStyle;

/**
 * @Block(
 *   id = "latest_projects_block",
 *   admin_label = @Translation("3 последних проекта"),
 * )
 */
class LatestProjectBlock extends BlockBase {

  public function build() {
    return [
      '#markup' => '<div id="latest-projects-container"></div>',
      '#attached' => [
        'library' => [
          'projects_type/projects_api',
        ],
        'drupalSettings' => [
          'projectsType' => [
            'ajaxPath' => '/projects-type/latest-json',
          ],
        ],
      ],
    ];
  }
}
