<?php

namespace App\Permissions;

use ReflectionClass;

class PermissionRegistry
{
    // =========================role Permissions=========================
    const ADMIN_ROLES_INDEX = 'roles.index';

    const ADMIN_ROLES_STORE = 'roles.store';

    const ADMIN_ROLES_SHOW = 'roles.show';

    const ADMIN_ROLES_UPDATE = 'roles.update';

    const ADMIN_ROLES_DESTROY = 'roles.destroy';
    // =========================role Permissions========================

    // =========================permission========================
    const ADMIN_PERMISSIONS_INDEX = 'permissions.index';

    const ADMIN_PERMISSIONS_SHOW = 'permissions.show';

    const ADMIN_PERMISSIONS_DESTROY = 'permissions.destroy';
    // =========================permission========================

    // =========================attribute Permissions=========================
    public const ADMIN_ATTRIBUTES_INDEX = 'admin.attributes.index';

    public const ADMIN_ATTRIBUTES_STORE = 'admin.attributes.store';

    public const ADMIN_ATTRIBUTES_SHOW = 'admin.attributes.show';

    public const ADMIN_ATTRIBUTES_UPDATE = 'admin.attributes.update';

    public const ADMIN_ATTRIBUTES_DESTROY = 'admin.attributes.destroy';
    // =========================attribute Permissions===========================

    // =========================city Permissions===========================
    public const ADMIN_CITIES_INDEX = 'admin.cities.index';

    public const ADMIN_CITIES_STORE = 'admin.cities.store';

    public const ADMIN_CITIES_SHOW = 'admin.cities.show';

    public const ADMIN_CITIES_UPDATE = 'admin.cities.update';

    public const ADMIN_CITIES_DESTROY = 'admin.cities.destroy';
    // =========================city Permissions===========================

    // =========================== Country Permissions ===========================
    const COUNTRY_INDEX = 'country.index';

    const COUNTRY_STORE = 'country.store';

    const COUNTRY_SHOW = 'country.show';

    const COUNTRY_UPDATE = 'country.update';

    const COUNTRY_DESTROY = 'country.destroy';
    // =========================== Country Permissions ===========================

    // ===========================property Permissions ===========================
    public const ADMIN_PROPERTIES_INDEX = 'admin.properties.index';

    public const ADMIN_PROPERTIES_STORE = 'admin.properties.store';

    public const ADMIN_PROPERTIES_SHOW = 'admin.properties.show';

    public const ADMIN_PROPERTIES_UPDATE = 'admin.properties.update';

    public const ADMIN_PROPERTIES_DESTROY = 'admin.properties.destroy';
    // ===========================property Permissions ===========================

    // ===========================property_type Permissions =======================
    public const ADMIN_PROPERTY_TYPES_INDEX = 'admin.property_types.index';

    public const ADMIN_PROPERTY_TYPES_STORE = 'admin.property_types.store';

    public const ADMIN_PROPERTY_TYPES_SHOW = 'admin.property_types.show';

    public const ADMIN_PROPERTY_TYPES_UPDATE = 'admin.property_types.update';

    public const ADMIN_PROPERTY_TYPES_DESTROY = 'admin.property_types.destroy';
    // ===========================property_type Permissions =======================

    // ===========================state Permissions ======================
    public const ADMIN_STATES_INDEX = 'admin.states.index';

    public const ADMIN_STATES_STORE = 'admin.states.store';

    public const ADMIN_STATES_SHOW = 'admin.states.show';

    public const ADMIN_STATES_UPDATE = 'admin.states.update';

    public const ADMIN_STATES_DESTROY = 'admin.states.destroy';
    // ===========================state Permissions ======================

    // ================= Units Permissions =================
    public const ADMIN_UNITS_INDEX = 'admin.units.index';

    public const ADMIN_UNITS_SHOW = 'admin.units.show';

    public const ADMIN_UNITS_STORE = 'admin.units.store';

    public const ADMIN_UNITS_UPDATE = 'admin.units.update';

    public const ADMIN_UNITS_DESTROY = 'admin.units.destroy';

    // ================= Units Permissions =================

    // ================= Admin orders Permissions =================

    public const ADMIN_ORDERS_INDEX = 'admin.orders.index';

    public const ADMIN_ORDERS_SHOW = 'admin.orders.show';

    public const ADMIN_ORDERS_APPROVE = 'admin.orders.approve';

    public const ADMIN_ORDERS_REJECT = 'admin.orders.reject';

    // ================= Admin orders Permissions =================

    // ==================== Developer Permissions =================
    public const ADMIN_DEVELOPERS_INDEX = 'admin.developers.index';

    public const ADMIN_DEVELOPERS_STORE = 'admin.developers.store';

    public const ADMIN_DEVELOPERS_SHOW = 'admin.developers.show';

    public const ADMIN_DEVELOPERS_UPDATE = 'admin.developers.update';

    public const ADMIN_DEVELOPERS_DESTROY = 'admin.developers.destroy';
    // ==================== Developer Permissions =================

    // ==================== Phase Permissions ======================
    public const ADMIN_PHASES_INDEX = 'admin.phases.index';

    public const ADMIN_PHASES_STORE = 'admin.phases.store';

    public const ADMIN_PHASES_SHOW = 'admin.phases.show';

    public const ADMIN_PHASES_UPDATE = 'admin.phases.update';

    public const ADMIN_PHASES_DESTROY = 'admin.phases.destroy';

    // ==================== Phase Permissions ======================

    // ==================== Project Permissions ======================
    public const ADMIN_PROJECTS_INDEX = 'admin.projects.index';

    public const ADMIN_PROJECTS_STORE = 'admin.projects.store';

    public const ADMIN_PROJECTS_SHOW = 'admin.projects.show';

    public const ADMIN_PROJECTS_UPDATE = 'admin.projects.update';

    public const ADMIN_PROJECTS_DESTROY = 'admin.projects.destroy';
    // ==================== Project Permissions ======================

    public static function all(): array
    {
        $reflection = new ReflectionClass(__CLASS__);
        $constants = $reflection->getConstants();

        return array_values($constants);
    }

    public static function exists(string $permission): bool
    {
        return in_array($permission, self::all());
    }
}
