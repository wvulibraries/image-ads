SELECT DISTINCT * FROM imageAds
    LEFT JOIN displayConditions
        ON imageAds.ID = displayConditions.imageAdID
            WHERE enabled='1'
                AND (displayConditions.dateStart <= $now AND displayConditions.dateEnd >= $now),
                AND (displayConditions.dateStart is NULL AND displayConditions.dateEnd is NULL),
                AND (
                    IF displayCondition.monday = 1 AND $currentWeekday == 'Monday' THEN

                )


    SELECT DISTINCT * FROM imageAds
        LEFT JOIN displayConditions ON imageAds.ID = displayConditions.imageAdID
            WHERE enabled='1'
                AND
                ORDER BY priority DESC;