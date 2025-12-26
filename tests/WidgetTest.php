<?php

use App\Core\Widget;

$html = Widget::render('div', ['class' => 'box'], 'Hello');

assertEquals(
  '<div class="box">Hello</div>',
  $html,
  'Widget should render HTML correctly'
);

echo "WidgetTest passed\n";
