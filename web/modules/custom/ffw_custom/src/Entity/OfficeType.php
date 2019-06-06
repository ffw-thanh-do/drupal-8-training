<?php

namespace Drupal\ffw_custom\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Office type entity.
 *
 * @ConfigEntityType(
 *   id = "office_type",
 *   label = @Translation("Office type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\ffw_custom\OfficeTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\ffw_custom\Form\OfficeTypeForm",
 *       "edit" = "Drupal\ffw_custom\Form\OfficeTypeForm",
 *       "delete" = "Drupal\ffw_custom\Form\OfficeTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\ffw_custom\OfficeTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "office_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "office",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/content/office_type/{office_type}",
 *     "add-form" = "/admin/content/office_type/add",
 *     "edit-form" = "/admin/content/office_type/{office_type}/edit",
 *     "delete-form" = "/admin/content/office_type/{office_type}/delete",
 *     "collection" = "/admin/content/office_type"
 *   }
 * )
 */
class OfficeType extends ConfigEntityBundleBase implements OfficeTypeInterface {

  /**
   * The Office type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Office type label.
   *
   * @var string
   */
  protected $label;

}
