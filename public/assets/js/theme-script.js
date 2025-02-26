
function loadJS(FILE_URL, async = true) {
	let scriptEle = document.createElement("script");

	scriptEle.setAttribute("src", FILE_URL);
	scriptEle.setAttribute("type", "text/javascript");
	scriptEle.setAttribute("async", async);

	document.body.appendChild(scriptEle);

	// success event
	scriptEle.addEventListener("load", () => {

	});
	 // error event
	scriptEle.addEventListener("error", (ev) => {

	});
  }





