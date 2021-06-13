<?php

namespace WPJsonSchemas;

$sites = get_sites();

save_object_array( $sites, 'site' );
