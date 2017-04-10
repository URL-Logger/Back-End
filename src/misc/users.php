<?php
$_DB_USERS = array(
	"r_u_login"=> array(
		'Allow'=>array("%"),
		'Name'=>"READ_USER_LOGIN",
		'Password'=>"yWVpuU9oRaGZtC8xH3WTNWrL",
		'Access'=>array(
			'User_Login'=>"SELECT"
		)
	),
	"r_u_info"=> array(
		'Allow'=>array("%"),
		'Name'=>"READ_USER_INFO",
		'Password'=>"FDigkmMR9Z70azNFWAcgN18m",
		'Access'=>array(
			'User_Login'=>"SELECT",
			'User_Info'=>"SELECT"
		)
	),
	"w_u_info"=> array(
		'Allow'=>array("%"),
		'Name'=>"WRITE_USER_INFO",
		'Password'=>"O5NttGGK9IFmU0Q4tawrHCJN",
		'Access'=>array(
			'User_Login'=>"SELECT(ID), INSERT, UPDATE",
			'User_Info'=>"SELECT(ID), INSERT, UPDATE"
		)
	),
	"r_collection"=> array(
		'Allow'=>array("%"),
		'Name'=>"READ_COLLECTION",
		'Password'=>"Cj9nbCIsjbZUqyzkcafbcIht",
		'Access'=>array(
			'Collection_Chrome'=>"SELECT",
			'Collection_Android'=>"SELECT"
		)
	),
	"w_collection"=> array(
		'Allow'=>array("%"),
		'Name'=>"WRITE_COLLECTION",
		'Password'=>"BOvB5qrMm5rp72JSeNgGAIkw",
		'Access'=>array(
			'Collection_Chrome'=>"INSERT",
			'Collection_Android'=>"INSERT"
		)
	),
	"r_a_login"=> array(
		'Allow'=>array("%"),
		'Name'=>"READ_ADMIN_LOGIN",
		'Password'=>"olbBPB8uHiZHgFoyII1NmS7N",
		'Access'=>array(
			'Admin_Login'=>"SELECT"
		)
	),
	"r_a_info"=> array(
		'Allow'=>array("%"),
		'Name'=>"READ_ADMIN_INFO",
		'Password'=>"mwqUk97h5v4apyqCh2fmlNt",
		'Access'=>array(
			'Admin_Login'=>"SELECT"
		)
	),
	"w_a_info"=> array(
		'Allow'=>array("%"),
		'Name'=>"WRITE_ADMIN_INFO",
		'Password'=>"miAsef6r19II8N6SOOCZpkjf",
		'Access'=>array(
			'Admin_Login'=>"SELECT(ID), INSERT, UPDATE"
		)
	),
	"r_s_login"=> array(
		'Allow'=>array("%"),
		'Name'=>"READ_SECURITY_LOGIN",
		'Password'=>"4xuU3xYG6a9MaYzN0c8Bjser",
		'Access'=>array(
			'Security_Salt'=>"SELECT"
		)
	),
	"w_s_login"=> array(
		'Allow'=>array("%"),
		'Name'=>"WRITE_SECURITY_LOGIN",
		'Password'=>"wBLsrDZJZESx9PRZ2TBoVx2u",
		'Access'=>array(
			'Security_Salt'=>"SELECT(ID), INSERT, UPDATE"
		)
	),
	"r_s_recover"=> array(
		'Allow'=>array("%"),
		'Name'=>"READ_SECURITY_RECOVER",
		'Password'=>"tt3FQMvPo7UXe8kbe2254hZp",
		'Access'=>array(
			'Security_Recover_Admin'=>"SELECT"
		)
	),
	"w_s_recover"=> array(
		'Allow'=>array("%"),
		'Name'=>"WRITE_SECURITY_RECOVER",
		'Password'=>"xoXxtey8LP7CITAYQphHeaIe",
		'Access'=>array(
			'Security_Recover_Admin'=>"SELECT(ID), INSERT, UPDATE"
		)
	),
	"r_opportunities"=> array(
		'Allow'=>array("%"),
		'Name'=>"READ_OPPORTUNITIES",
		'Password'=>"tJCvDavPGe0Dm9otGNHpAcoF",
		'Access'=>array(
			'Opportunities_Respondents'=>"SELECT"
		)
	),
	"w_opportunities"=> array(
		'Allow'=>array("%"),
		'Name'=>"WRITE_OPPORTUNITIES",
		'Password'=>"gN9LyNWpb4R13OHsAWvBHCbL",
		'Access'=>array(
			'Opportunities_Respondents'=>"SELECT(project_id), INSERT, UPDATE"
		)
	)
);