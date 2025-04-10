<div class="slider-container">
    <div id="slidy-container">
        <figure id="slidy">
            <img src="./lib/images/library1.jpg" alt="library1">
            <img src="./lib/images/library2.jpg" alt="library2">
            <img src="./lib/images/library3.jpg" alt="library3">
            <img src="./lib/images/library4.jpg" alt="library4">
            <img src="./lib/images/library5.jpg" alt="library5">
            <img src="./lib/images/library6.jpg" alt="library6">
            <img src="./lib/images/library7.jpg" alt="library7">
        </figure>
    </div>
</div>

<style>
.slider-container {
    width: 100%;
    overflow: hidden;
    margin-top: 20px;
}

#slidy-container {
    width: 100%;
    overflow: hidden;
}

#slidy {
    margin: 0;
    padding: 0;
    list-style: none;
    position: relative;
}

#slidy img {
    width: 100%;
    height: 400px; /* Fixed height for all images */
    object-fit: cover;
    display: block;
}
</style>
<script>
	var timeOnSlide = 3,
		timeBetweenSlides = 1,
		animationstring = 'animation',
		animation = false,
		keyframeprefix = '',
		domPrefixes = 'Webkit Moz O Khtml'.split(' '),
		pfx = '',
		slidy = document.getElementById("slidy");
	if (slidy.style.animationName !== undefined) {
		animation = true;
	}
	if (animation === false) {
		for (var i = 0; i < domPrefixes.length; i++) {
			if (slidy.style[domPrefixes[i] + 'AnimationName'] !== undefined) {
				pfx = domPrefixes[i];
				animationstring = pfx + 'Animation';
				keyframeprefix = '-' + pfx.toLowerCase() + '-';
				animation = true;
				break;
			}
		}
	}
	if (animation === false) {
		// animate using a JavaScript fallback, if you wish
	} else {
		var images = slidy.getElementsByTagName("img"),
			firstImg = images[0],
			imgWrap = firstImg.cloneNode(false);
		slidy.appendChild(imgWrap);
		var imgCount = images.length,
			totalTime = (timeOnSlide + timeBetweenSlides) * (imgCount - 1),
			slideRatio = (timeOnSlide / totalTime) * 100,
			moveRatio = (timeBetweenSlides / totalTime) * 100,
			basePercentage = 100 / imgCount,
			position = 0,
			css = document.createElement("style");
		css.type = "text/css";
		css.innerHTML += "#slidy { text-align: left; margin: 0; font-size: 0; position: relative; width: " + (imgCount * 100) + "%; }";
		css.innerHTML += "#slidy img { float: left; width: " + basePercentage + "%; }";
		css.innerHTML += "@" + keyframeprefix + "keyframes slidy {";
		for (i = 0; i < (imgCount - 1); i++) {
			position += slideRatio;
			css.innerHTML += position + "% { left: -" + (i * 100) + "%; }";
			position += moveRatio;
			css.innerHTML += position + "% { left: -" + ((i + 1) * 100) + "%; }";
		}
		css.innerHTML += "}";
		css.innerHTML += "#slidy { left: 0%; " + keyframeprefix + "transform: translate3d(0,0,0); " + keyframeprefix + "animation: " + totalTime + "s slidy infinite; }";
		document.body.appendChild(css);
	}
</script>