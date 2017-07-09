function initMarketBoxMachine() {
	jQuery('.MarketBoxMachine').each(function(ind){
		var $this = jQuery(this).attr('ind', ind);
		$this.find('.ImgsBoxMBM div').each(function(ind){
			var $this = jQuery(this).attr('ind', ind),
				bar = $this.parents('.MarketBoxMachine').find('.NavBarMBM'),
				navNode = jQuery('<div class="circle"></div>').appendTo(bar)
					.attr('ind', ind);
		});
		$this.find('.LinkssBoxMBM div').each(function(ind){
			jQuery(this).attr('ind', ind);
		});

		$this.attr('num', $this.find('.ImgsBoxMBM div').length);

		$this.find('.NavBarMBM div').attr('enable', 0).one('click', mbm_stopAnimate);
		mbm_switchTo($this.find('.NavBarMBM div').first());
		
		var timer = setInterval(mbm_animate, 5000, $this);
		$this.attr('animating', '1').attr('bw', 0).attr('timerMBM', timer);
	});
}

function mbm_animate(obj) {
	var $this = obj,
		num = $this.attr('num'),
		bw = $this.attr('bw'),
		enabled = $this.find('.NavBarMBM div[enable=1]');
	if ( bw == 1 ) {
		if (0 < +enabled.attr('ind')) {
			mbm_switchTo(enabled.prev());
		} else {
			mbm_switchTo(enabled.next());
			$this.attr('bw', 0);
		}
	} else {
		if (num - 1 > +enabled.attr('ind')) {
			mbm_switchTo(enabled.next());
		} else {
			mbm_switchTo(enabled.prev());
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
		img = MBM.find('.ImgsBoxMBM div[ind="'+obj.attr('ind')+'"]'),
		link = MBM.find('.LinksBoxMBM div[ind="'+obj.attr('ind')+'"]');

	MBM.find('.NavBarMBM div').attr('enable', 0);
	obj.attr('enable', 1);
	
	MBM.find('.ImgBoxMBM')
		.css('background-image', 'url('+img.text()+')')
		.parents('a')
			.attr('href', link.text());

}

function mbm_stopAnimate(event) {
	event.preventDefault();

	var $this = jQuery(this),
		MBM = $this.parents('.MarketBoxMachine').attr('animating', 0);
	
	clearInterval(MBM.attr('timerMBM'));
	$this.parents('.NavBarMBM').children().on('click', mbm_click);
	mbm_switchTo($this);
}

jQuery(document).ready(function(){
	initMarketBoxMachine();
});