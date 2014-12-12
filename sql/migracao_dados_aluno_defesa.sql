-- migração de dados para tabela de defesas


-- antes de executar script no banco de dados, colocar j17_defesa.idDefesa com autoincrement
insert into j17_defesa (aluno_id, conceito, tipoDefesa, data, titulo, local, horario, resumo)
select id, conceitoQual2, 'Q1',  str_to_date(dataQual2, '%d/%m/%Y'), tituloQual2,
localQual2, horarioQual2, resumoQual2
from j17_aluno 
where 
(str_to_date(dataQual2, '%d/%m/%Y') <> '00-00-0000')
and 
((tituloQual2 <> '') and (tituloQual2 is not null) and (length(tituloQual2) > 0))  
and curso = 1;

insert into j17_defesa (aluno_id, data, titulo, tipoDefesa, conceito, resumo, local, horario, numDefesa)
select id,  str_to_date(dataTese, '%d/%m/%Y'), tituloTese, 'D', conceitoTese, resumoTese, localTese, horarioTese, numDefesa
from j17_aluno 
where 
(str_to_date(dataTese, '%d/%m/%Y')  <> '00-00-0000')
and 
((tituloTese <> '') and (tituloTese is not null) and (length(tituloTese) > 0))  
and curso = 1; 

insert into j17_defesa (aluno_id, data, titulo, tipoDefesa, conceito)
select id, str_to_date(dataQual1, '%d/%m/%Y'), tituloQual1, 'Q1', conceitoQual1 
from j17_aluno 
where 
(str_to_date(dataQual1, '%d/%m/%Y') <> '00-00-0000')
and 
((tituloQual1 <> '') and (tituloQual1 is not null) and (length(tituloQual1) > 0))  
and curso = 2;

insert into j17_defesa (aluno_id, data, titulo, tipoDefesa, conceito, resumo, local, horario)
select id,  str_to_date(dataQual2, '%d/%m/%Y') dataq2, tituloQual2, 'Q1', conceitoQual2, resumoQual2, localQual2, horarioQual2
from j17_aluno 
where 
(str_to_date(dataQual2, '%d/%m/%Y')  <> '00-00-0000')
and 
((tituloQual1 <> '') and (tituloQual1 is not null) and (length(tituloQual1) > 0))  
and curso = 2;

insert into j17_defesa (aluno_id, data, titulo, tipoDefesa, conceito, resumo, local, horario, numDefesa)
select id,  str_to_date(dataTese, '%d/%m/%Y'), tituloTese, 'T', conceitoTese, resumoTese, localTese, horarioTese, numDefesa
from j17_aluno 
where 
(str_to_date(dataTese, '%d/%m/%Y')  <> '00-00-0000')
and 
((tituloTese <> '') and (tituloTese is not null) and (length(tituloTese) > 0))  
and curso	 = 2;