<?php
function component_listItemEntrystep($step, $caption, $path, $isActiveClass = '') {
  $classes = array(
      'p-entrystep__item--' . esc_attr($step),
      $isActiveClass,
  );

  echo '<li class="' . esc_attr(implode(' ', $classes)) . '">';
  echo '<svg class="p-entrystep__icon" data-slot="icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">';

  switch ($step) {
      case 'input':
          echo '<path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z"></path>';
          break;
      case 'confirm':
          echo '<path clip-rule="evenodd" fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z"></path>';
          break;
      case 'send':
          echo '<path d="M3.478 2.404a.75.75 0 0 0-.926.941l2.432 7.905H13.5a.75.75 0 0 1 0 1.5H4.984l-2.432 7.905a.75.75 0 0 0 .926.94 60.519 60.519 0 0 0 18.445-8.986.75.75 0 0 0 0-1.218A60.517 60.517 0 0 0 3.478 2.404Z"></path>';
          break;
      default:
          break;
  }

  echo '</svg>';
  echo '<span class="p-entrystep__title"><span class="p-entrystep__title--caption">ステップ' . esc_html($caption) . '</span>' . esc_html($path) . '</span>';
  echo '</li>';
}
?>