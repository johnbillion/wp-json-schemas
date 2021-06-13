<?php

namespace WPJsonSchemas;

$roles = wp_roles()->role_objects;

save_object_array( $roles, 'role' );
