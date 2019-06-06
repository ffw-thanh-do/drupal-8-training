<?php

namespace Drupal\ffw_custom\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Office entities.
 *
 * @ingroup ffw_custom
 */
interface OfficeInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Office name.
   *
   * @return string
   *   Name of the Office.
   */
  public function getName();

  /**
   * Sets the Office name.
   *
   * @param string $name
   *   The Office name.
   *
   * @return \Drupal\ffw_custom\Entity\OfficeInterface
   *   The called Office entity.
   */
  public function setName($name);

  /**
   * Gets the Office creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Office.
   */
  public function getCreatedTime();

  /**
   * Sets the Office creation timestamp.
   *
   * @param int $timestamp
   *   The Office creation timestamp.
   *
   * @return \Drupal\ffw_custom\Entity\OfficeInterface
   *   The called Office entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Office published status indicator.
   *
   * Unpublished Office are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Office is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Office.
   *
   * @param bool $published
   *   TRUE to set this Office to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\ffw_custom\Entity\OfficeInterface
   *   The called Office entity.
   */
  public function setPublished($published);

  /**
   * Gets the Office revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Office revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\ffw_custom\Entity\OfficeInterface
   *   The called Office entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Office revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Office revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\ffw_custom\Entity\OfficeInterface
   *   The called Office entity.
   */
  public function setRevisionUserId($uid);

}
