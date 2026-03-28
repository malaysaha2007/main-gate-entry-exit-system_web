<?php
/**
 * Build MongoDB filter for activity logs
 * based on role & hostel
 */

function buildLogFilter(string $currentUserRole, ?string $currentUserHostel): array
{
    /* ---------------- ADMIN LEVEL ---------------- */
    if (
        $currentUserRole === "Director" ||
        $currentUserRole === "Dean Academic" ||
        $currentUserRole === "Main Gate Guard"
    ) {
        // Admin-level users can see ALL logs
        return [];
    }

    /* ---------------- HOSTEL LEVEL ---------------- */
    if ($currentUserHostel) {
        return [
            "hostel" => $currentUserHostel
        ];
    }

    /* ---------------- SAFETY FALLBACK ---------------- */
    // If something is wrong, return no logs
    return [
        "_id" => null
    ];
}
