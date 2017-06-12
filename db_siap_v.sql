CREATE VIEW `v_shift` AS select `setting`.`param_name` AS `param_name`,`setting`.`param_value` AS `param_value`,(case when (`setting`.`param_name` = 'range_before_in') then 'Durasi sebelum jam masuk' else (case when (`setting`.`param_name` = 'range_after_in') then 'Durasi setelah jam masuk' else (case when (`setting`.`param_name` = 'range_before_out') then 'Durasi sebelum jam pulang' else (case when (`setting`.`param_name` = 'range_after_out') then 'Durasi setelah jam pulang' else (case when (`setting`.`param_name` = 'late_tolerance') then 'Toleransi terlambat' else (case when (`setting`.`param_name` = 'early_tolerance') then 'Toleransi pulang awal' else (case when (`setting`.`param_name` = 'minim_count_as') then 'Hitung kerja 1/2 hari jika kerja minimal' else 'none' end) end) end) end) end) end) end) AS `disp_field`,(case when (`setting`.`param_name` = 'range_before_in') then '1' else (case when (`setting`.`param_name` = 'range_after_in') then '2' else (case when (`setting`.`param_name` = 'range_before_out') then '3' else (case when (`setting`.`param_name` = 'range_after_out') then '4' else (case when (`setting`.`param_name` = 'late_tolerance') then '5' else (case when (`setting`.`param_name` = 'early_tolerance') then '6' else (case when (`setting`.`param_name` = 'minim_count_as') then '7' else 'none' end) end) end) end) end) end) end) AS `disp_no` from `setting` where ((`setting`.`param_name` = 'range_before_in') or (`setting`.`param_name` = 'range_after_in') or (`setting`.`param_name` = 'range_before_out') or (`setting`.`param_name` = 'range_after_out') or (`setting`.`param_name` = 'late_tolerance') or (`setting`.`param_name` = 'early_tolerance') or (`setting`.`param_name` = 'minim_count_as')) order by (case when (`setting`.`param_name` = 'range_before_in') then '1' else (case when (`setting`.`param_name` = 'range_after_in') then '2' else (case when (`setting`.`param_name` = 'range_before_out') then '3' else (case when (`setting`.`param_name` = 'range_after_out') then '4' else (case when (`setting`.`param_name` = 'late_tolerance') then '5' else (case when (`setting`.`param_name` = 'early_tolerance') then '6' else (case when (`setting`.`param_name` = 'minim_count_as') then '7' else 'none' end) end) end) end) end) end) end);
