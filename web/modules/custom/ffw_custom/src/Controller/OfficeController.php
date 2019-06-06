<?php

namespace Drupal\ffw_custom\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\ffw_custom\Entity\OfficeInterface;

/**
 * Class OfficeController.
 *
 *  Returns responses for Office routes.
 */
class OfficeController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Office  revision.
   *
   * @param int $office_revision
   *   The Office  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($office_revision) {
    $office = $this->entityManager()->getStorage('office')->loadRevision($office_revision);
    $view_builder = $this->entityManager()->getViewBuilder('office');

    return $view_builder->view($office);
  }

  /**
   * Page title callback for a Office  revision.
   *
   * @param int $office_revision
   *   The Office  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($office_revision) {
    $office = $this->entityManager()->getStorage('office')->loadRevision($office_revision);
    return $this->t('Revision of %title from %date', ['%title' => $office->label(), '%date' => format_date($office->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Office .
   *
   * @param \Drupal\ffw_custom\Entity\OfficeInterface $office
   *   A Office  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(OfficeInterface $office) {
    $account = $this->currentUser();
    $langcode = $office->language()->getId();
    $langname = $office->language()->getName();
    $languages = $office->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $office_storage = $this->entityManager()->getStorage('office');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $office->label()]) : $this->t('Revisions for %title', ['%title' => $office->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all office revisions") || $account->hasPermission('administer office entities')));
    $delete_permission = (($account->hasPermission("delete all office revisions") || $account->hasPermission('administer office entities')));

    $rows = [];

    $vids = $office_storage->revisionIds($office);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\ffw_custom\OfficeInterface $revision */
      $revision = $office_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $office->getRevisionId()) {
          $link = $this->l($date, new Url('entity.office.revision', ['office' => $office->id(), 'office_revision' => $vid]));
        }
        else {
          $link = $office->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->getRevisionLogMessage(), '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.office.translation_revert', ['office' => $office->id(), 'office_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.office.revision_revert', ['office' => $office->id(), 'office_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.office.revision_delete', ['office' => $office->id(), 'office_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['office_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
