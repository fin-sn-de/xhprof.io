<html>
<head>
<script type="text/javascript" src="<?=$config['url_static']?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?=$config['url_static']?>js/d3.v3.min.js"></script>

<style type="text/css">
.chart {
	display: block;
	margin: auto;
	margin-top: 20px;
}

text {
	font-size: 11px;
}

rect {
	fill: none;
}
</style>
</head>
<body>
<div id="body">
	<div id="footer">
		<div>
			<select>
				<option value="ct">Call Count</option>
				<option value="wt">Wall Time</option>
				<option value="cpu">CPU</option>
				<option value="mu">Memory Usage</option>
				<option value="pmu">Peak Memory Usage</option>
			</select>
		</div>
	</div>
</div>
<script type="text/javascript">

function ct(d) {
  return d.ct;
}
function wt(d) {
  return d.wt;
}
function cpu(d) {
  return d.cpu;
}
function mu(d) {
  return d.mu;
}
function pmu(d) {
  return d.pmu;
}
	
var w = Math.max(1200, $( document ).width() - 80),
    h = Math.max(600, $( document ).height() - 60),
    x = d3.scale.linear().range([0, w]),
    y = d3.scale.linear().range([0, h]),
    color = d3.scale.category20c(),
    root,
    node;

var treemap = d3.layout.treemap()
    .round(false)
    .size([w, h])
    .sticky(true)
    .value(ct);

var svg = d3.select("#body").append("div")
    .attr("class", "chart")
    .style("width", w + "px")
    .style("height", h + "px")
  .append("svg:svg")
    .attr("width", w)
    .attr("height", h)
  .append("svg:g")
    .attr("transform", "translate(.5,.5)");

d3.json("?xhprof[template]=api&xhprof[query][target]=treemap&xhprof[query][request_id]=<?php echo $_GET['xhprof']['query']['request_id']; ?>", function(data) {
  node = root = data;

  var nodes = treemap.nodes(root)
      .filter(function(d) { return !d.children; });

  var cell = svg.selectAll("g")
      .data(nodes)
    .enter().append("svg:g")
      .attr("class", "cell")
      .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; })
      .on("click", function(d) { return zoom(node == d.parent ? root : d.parent); });

  cell.append("svg:rect")
      .attr("width", function(d) { return d.dx - 1; })
      .attr("height", function(d) { return d.dy - 1; })
      .style("fill", function(d) { return color(d.parent.name); });

  cell.append("svg:text")
      .attr("x", function(d) { return d.dx / 2; })
      .attr("y", function(d) { return d.dy / 2; })
      .attr("dy", ".35em")
      .attr("text-anchor", "middle")
      .text(function(d) { return d.name; })
      .style("opacity", function(d) { 
          if (d.dy < 1) return 0; 
          d.w = this.getComputedTextLength(); 
          return d.dx > d.w ? 1 : 0; 
      });

  d3.select(window).on("click", function() { zoom(root); });

  d3.select("select").on("change", function() {

	var callback;
	switch(this.value) {
        case "ct": callback = ct; break;
        case "wt": callback = wt; break;
        case "cpu": callback = cpu; break;
        case "mu": callback = mu; break;
        case "pmu": callback = pmu; break;
	}
    treemap.value(callback).nodes(root);
    zoom(node);
  });
});

function zoom(d) {
  var kx = w / d.dx, ky = h / d.dy;
  x.domain([d.x, d.x + d.dx]);
  y.domain([d.y, d.y + d.dy]);

  var t = svg.selectAll("g.cell").transition()
      .duration(d3.event.altKey ? 7500 : 750)
      .attr("transform", function(d) { return "translate(" + x(d.x) + "," + y(d.y) + ")"; });

  t.select("rect")
      .attr("width", function(d) { return kx * d.dx - 1; })
      .attr("height", function(d) { return ky * d.dy - 1; })

  t.select("text")
      .attr("x", function(d) { return kx * d.dx / 2; })
      .attr("y", function(d) { return ky * d.dy / 2; })
      .style("opacity", function(d) { return kx * d.dx > d.w ? 1 : 0; });

  node = d;
  d3.event.stopPropagation();
}

</script>
</body></html>