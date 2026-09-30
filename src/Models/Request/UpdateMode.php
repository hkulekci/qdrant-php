<?php
/**
 * UpdateMode
 *
 * Behaviour of point upserts regarding existing points, available since Qdrant 1.17.
 *
 * https://qdrant.tech/documentation/concepts/points/#update-mode
 *
 * @since     Sep 2026
 * @author    Haydar KULEKCI <haydarkulekci@gmail.com>
 */

namespace Qdrant\Models\Request;

final class UpdateMode
{
    /** Insert new points and overwrite existing ones (default). */
    public const UPSERT = 'upsert';

    /** Only insert new points, existing points are left untouched. */
    public const INSERT_ONLY = 'insert_only';

    /** Only update existing points, new points are ignored. */
    public const UPDATE_ONLY = 'update_only';
}
