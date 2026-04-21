<?php

namespace Drupal\custom_schema_override\Plugin\metatag\Tag;

use Drupal\Core\Form\FormStateInterface;
use Drupal\metatag\Plugin\metatag\Tag\MetaNameBase;

/**
 * Custom JSON-LD override field for schema_metatag.
 *
 * @MetatagTag(
 *   id = "schema_json_override",
 *   label = @Translation("Custom Schema JSON-LD"),
 *   description = @Translation("Enter raw JSON-LD to override all schema_metatag structured data on this page. Must be a valid JSON object or array. Leave empty to use the default schema_metatag output."),
 *   name = "schema_json_override",
 *   group = "schema_json_override",
 *   weight = 1,
 *   type = "label",
 *   secure = FALSE,
 *   multiple = FALSE,
 *   long = TRUE,
 * )
 */
class SchemaJsonOverride extends MetaNameBase {

  /**
   * {@inheritdoc}
   */
  public function form(array $element = []): array {
    return [
      '#type' => 'textarea',
      '#title' => $this->label(),
      '#default_value' => $this->value(),
      '#required' => $element['#required'] ?? FALSE,
      '#description' => $this->description(),
      '#rows' => 12,
      '#element_validate' => [[get_class($this), 'validateTag']],
      '#attributes' => [
        'style' => 'font-family: monospace; font-size: 0.85em;',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   *
   * Emits a hidden marker meta tag carrying the raw JSON value so that
   * hook_page_attachments_alter() can detect and process it.  The marker
   * is stripped before any output reaches the browser.
   */
  public function output(): array {
    $value = trim((string) $this->value());
    if ($value === '') {
      return [];
    }

    json_decode($value);
    if (json_last_error() !== JSON_ERROR_NONE) {
      return [];
    }

    return [
      '#tag' => 'meta',
      '#attributes' => [
        'name' => 'custom_schema_json_override',
        'content' => $value,
        'custom_schema_json_override' => TRUE,
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function validateTag(array &$element, FormStateInterface $form_state): void {
    $value = trim($element['#value'] ?? '');
    if ($value === '') {
      return;
    }

    json_decode($value);
    if (json_last_error() !== JSON_ERROR_NONE) {
      $form_state->setError($element, t('The custom schema JSON-LD is not valid JSON. Error: @error', [
        '@error' => json_last_error_msg(),
      ]));
    }
  }

}
