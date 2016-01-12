# Migration that makes priority a set of numbers
# Data is going from BOOLEAN to TINYINT(3)
# Data is already 0 - 1 no other modifications need to be made
ALTER TABLE `imageAds` MODIFY `priority` tinyint(3) NULL;