<?php
$config['interceptor']['default'][] = 'Guid';
$config['interceptor']['default'][] = 'Browser';
$config['interceptor']['default'][] = 'Browser';
$config['interceptor']['default'][] = 'Version';
$config['interceptor']['exception']['Browser'] = array(
    'Web_Advice',
    'Resource'
);