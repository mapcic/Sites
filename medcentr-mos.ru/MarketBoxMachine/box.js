function initMarketBoxMachine() {
	jQuery('.MarketBoxMachine').each(function(ind){
		var $this = jQuery(this).attr('ind', ind);
		$this.find('.ImgsBoxMBM div').each(function(ind){
			var $this = jQuery(this).attr('ind', ind),
				bar = $this.parents('.MarketBoxMachine').find('.NavBarMBM'),
				navNode = jQuery('<div class="circle"></div>').appendTo(bar)
					.attr('ind', ind);
		});

		$this.find('.NavBarMBM div').attr('enable', 0).one('click', mbm_stopAnimate);
		mbm_switchTo($this.find('.NavBarMBM div').first());
		
		var timer = setInterval(mbm_animate, 5000, ind);
		$this.attr('animating', '1').attr('bw', 0).attr('timerMBM', timer);
	});
}

function mbm_animate(ind) {
	var $this = jQuery('.MarketBoxMachine[ind='+ind+']'),
		num = $this.attr('num'),
		bw = $this.attr('bw'),
		enabled = $this.find('.NavBarMBM div[enabled=1]');
	if ( bw == 1 ) {
		if (0 > +enabled.attr('ind')) {
			mbm_switchTo($this.find('.NavBarMBM div[ind='+(enabled.attr('ind')-1)+']'));
		} else {
			mbm_switchTo($this.find('.NavBarMBM div[ind='+(enabled.attr('ind')+1)+']'));
			$this.attr('bw', 0);
		}
	} else {
		if (num < +enabled.attr('ind')) {
			mbm_switchTo($this.find('.NavBarMBM div[ind='+(enabled.attr('ind')+1)+']'));
		} else {
			mbm_switchTo($this.find('.NavBarMBM div[ind='+(enabled.attr('ind')-1)+']'));
			$this.attr('bw', 1);
		}
	}
}

function mbm_click(event) {
	event.preventDefault();
	event.stopPropagation();

	mbm_switchTo(jQuery(this));
}

function mbm_switchTo(obj) {
	var MBM = obj.parents('.MarketBoxMachine'),
		img = MBM.find('.ImgsBoxMBM div[ind="'+obj.attr('ind')+'"]');

	MBM.find('.NavBarMBM div').attr('enable', 0);
	obj.attr('enable', 1);
	
	MBM.find('.ImgBoxMBM')
		.css('background-image', 'url('+img.text()+')');

}

function mbm_stopAnimate(event) {
	event.preventDefault();

	var $this = jQuery(this),
		MBM = $this.parents('.MarketBoxMachine').attr('animating', 0);
	
	clearInterval(MBM.attr('timerMBM'));
	$this.find('div').on('click', mbm_click);
}

jQuery(document).ready(function(){
	initMarketBoxMachine();
});