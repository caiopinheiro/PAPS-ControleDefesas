$(document).ready( function() {
	var nav = '<span style="display:block; text-align:right; padding:0 20px; margin-bottom:-10px; font-size:small;" ><a href="javascript:;" rel="jfaq_expand" title="Expandir todos">Expandir</a> | <a href="javascript:;" rel="jfaq_collapse" title="Esconder todos">Esconder</a></span>';
	$( nav ).insertBefore( '#jfaq' );
	$( '#jfaq' ).addClass( 'simple_jfaq' );
	$( '#jfaq dd' ).css( 'display', 'none' );
	$( '#jfaq dt' ).css( 'cursor', 'pointer' )
		.click( function() { $(this).next().slideToggle( 'fast' ); })
		.hover( function () { $(this).addClass("hover"); }, function () { $(this).removeClass( 'hover' ); } );
	$( 'a[rel=jfaq_expand]' ).click( function() { $( '#jfaq dd:hidden' ).slideToggle( 'fast' ); });
	$( 'a[rel=jfaq_collapse]' ).click( function() { $( '#jfaq dd:visible' ).slideToggle( 'fast' ); });
});

/* Para a listagem das alocações de bolsa */