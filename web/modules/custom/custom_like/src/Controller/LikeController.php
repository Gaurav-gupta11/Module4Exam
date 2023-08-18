<?php

namespace Drupal\custom_like\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class LikeController.
 *
 * @package Drupal\custom_like\Controller
 */
class LikeController extends ControllerBase
{

  /**
   * Increase the like count of a node.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The node to like.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   A JSON response containing the number of likes.
   */
  public function like(NodeInterface $node)
  {
    $likes = $node->get('field_likes')->value;
    $node->set('field_likes', ++$likes);
    $node->save();

    return new JsonResponse(['likes' => $likes]);
  }
}
