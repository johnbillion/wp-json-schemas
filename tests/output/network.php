<?php

namespace WPJsonSchemas;

$networks = get_networks();

save_object_array( $networks, 'network' );
