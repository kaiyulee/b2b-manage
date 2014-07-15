$(function(){
	var iNow = null;
	var $aLi=$('ol li')
	var timer=null
	star()
	  $aLi.each(function(index) {
        $aLi.mouseover(function(){
		  iNow=$(this).index()
		  $(this).addClass('active')
		  .siblings().removeClass('active')
		   tab(iNow)
	    })
    });
	  $('.pic_nav').mouseover(function(){
	     clearInterval(timer)
	  })
	  $('.pic_nav').mouseout(function(){
	    star()
	  })
	  
	function tab(n)
	{
		$('.pic_nav_top').stop(true).animate({
			'left':'-'+n*604+'px'
		},300)
	}
	function star(){
		 timer=setInterval(function(){
			 iNow++
			 if(iNow==4)
			 {
				 iNow=0
			 }
			 tab(iNow)
			 $aLi.eq(iNow).addClass('active')
		     .siblings().removeClass('active')
		 },3000)
	 }
})
