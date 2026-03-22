<?php
function component_dlItemMenu($menuItems, $even = false) {
  foreach ($menuItems as $index => $menuItem) {
    echo '<dt';
    if ($index === 0) {
      echo ' class="menu__dt--first';
      if ($even) { echo '-even'; }
      echo '"';
    }
    echo '>' . $menuItem['title'] . '</dt>';
    echo '<dd>';
    if (isset($menuItem['caption']) && $menuItem['caption'] !== '') { echo '<p class="menu__caption">' . $menuItem['caption'] . '</p>'; }
    echo '<p class="menu__price">' . $menuItem['price'] . '</p>';
    echo '</dd>';
  }
}
?>