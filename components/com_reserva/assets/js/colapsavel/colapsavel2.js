$(document).ready( function() {
	var nav = '<span style="display:block; text-align:right; padding:0 10px; margin-bottom:-30px; font-size:small;" ><a href="javascript:;" rel="jfaq_expand">Expandir</a> | <a href="javascript:;" rel="jfaq_collapse">Esconder</a></span>';
	$( nav ).insertBefore( '#jfaq2' );
	$( '#jfaq2' ).addClass( 'simple_jfaq2' );
	$( '#jfaq2 dd' ).css( 'display', 'none' );
	$( '#jfaq2 dt' ).css( 'cursor', 'pointer' )
		.click( function() { $(this).next().slideToggle( 'fast' ); })
		.hover( function () { $(this).addClass("hover"); }, function () { $(this).removeClass( 'hover' ); } );
	$( 'a[rel=jfaq_expand]' ).click( function() { $( '#jfaq2 dd:hidden' ).slideToggle( 'fast' ); });
	$( 'a[rel=jfaq_collapse]' ).click( function() { $( '#jfaq2 dd:visible' ).slideToggle( 'fast' ); });
});

/* Para o histórico de alocações */