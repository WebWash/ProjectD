<?php

namespace Drupal\projectd_custom;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * @todo Add description for this breadcrumb builder.
 */
final class TaskBreadcrumbBuilder implements BreadcrumbBuilderInterface {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    // This breadcrumb builder should only apply to task nodes.
    $node = $route_match->getParameter('node');
    if (!empty($node) && $node->bundle() == 'task') {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $route_match): Breadcrumb {
    $breadcrumb = new Breadcrumb();
    $breadcrumb->addLink(Link::createFromRoute('Home', '<front>'));

    // Get the current node.
    $node = $route_match->getParameter('node');

    // Get the referenced project.
    $project = $node->field_project->entity;
    if ($project) {
      $breadcrumb->addLink(Link::createFromRoute($project->getTitle(), 'entity.node.canonical', ['node' => $project->id()]));
    }

    // Get the parent task.
    $parent_task = $node->field_parent_task->entity;
    if ($parent_task) {
      $breadcrumb->addLink(Link::createFromRoute($parent_task->getTitle(), 'entity.node.canonical', ['node' => $parent_task->id()]));
    }

    // Add the current task page.
    $breadcrumb->addLink(Link::createFromRoute($node->getTitle(), 'entity.node.canonical', ['node' => $node->id()]));

    return $breadcrumb;


  }

}
