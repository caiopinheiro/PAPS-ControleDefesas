ALTER TABLE `icomp`.`j17_banca_has_membrosbanca` 
ADD COLUMN `passagem` CHAR NULL DEFAULT 'N' AFTER `funcao`;
ALTER TABLE `icomp`.`j17_defesa` 
ADD COLUMN `previa` VARCHAR(45) NULL AFTER `aluno_id`;
