<html>
<head>
<script type="text/javascript"
	src="<?=$config['url_static']?>js/jquery.min.js"></script>
<script type="text/javascript"
	src="<?=$config['url_static']?>js/d3.v3.min.js"></script>

<style type="text/css">
.chart {
  display: block;
  margin: auto;
  margin-top: 60px;
  font-size: 11px;
}

rect {
  stroke: #eee;
  fill: #aaa;
  fill-opacity: .8;
}

rect.parent {
  cursor: pointer;
  fill: steelblue;
}

text {
  pointer-events: none;
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
h = Math.max(600, $( document ).height() - 100),
x = d3.scale.linear().range([0, w]),
y = d3.scale.linear().range([0, h]);

var vis = d3.select("#body").append("div")
.attr("class", "chart")
.style("width", w + "px")
.style("height", h + "px")
.append("svg:svg")
.attr("width", w)
.attr("height", h);

var partition = d3.layout.partition().value(ct);

d3.json("?xhprof[template]=api&xhprof[query][target]=treemap&xhprof[query][request_id]=<?php echo $_GET['xhprof']['query']['request_id']; ?>", function(root) {
    var g = vis.selectAll("g")
      .data(partition.nodes(root))
    .enter().append("svg:g")
      .attr("transform", function(d) { return "translate(" + x(d.y) + "," + y(d.x) + ")"; })
      .on("click", click);
    
    var kx = w / root.dx,
      ky = h / 1;
    
    g.append("svg:rect")
      .attr("width", root.dy * kx)
      .attr("height", function(d) { return d.dx * ky; })
      .attr("class", function(d) { return d.children ? "parent" : "child"; });
    
    g.append("svg:text")
      .attr("transform", transform)
      .attr("dy", ".35em")
      .style("opacity", function(d) { return d.dx * ky > 12 ? 1 : 0; })
      .text(function(d) { return d.name; })
    
    d3.select("select").on("change", function() {
        var callback;
        switch(this.value) {
            case "ct": callback = ct; break;
            case "wt": callback = wt; break;
            case "cpu": callback = cpu; break;
            case "mu": callback = mu; break;
            case "pmu": callback = pmu; break;
        }
        partition.value(callback).nodes(root);
        zoom(node);
    });
    
    d3.select(window)
      .on("click", function() { click(root); })
      
    function click(d) {
        if (!d.children) return;
        
        kx = (d.y ? w - 40 : w) / (1 - d.y);
        ky = h / d.dx;
        x.domain([d.y, 1]).range([d.y ? 40 : 0, w]);
        y.domain([d.x, d.x + d.dx]);
        
        var t = g.transition()
            .duration(d3.event.altKey ? 7500 : 750)
            .attr("transform", function(d) { return "translate(" + x(d.y) + "," + y(d.x) + ")"; });
        
        t.select("rect")
            .attr("width", d.dy * kx)
            .attr("height", function(d) { return d.dx * ky; });
        
        t.select("text")
            .attr("transform", transform)
            .style("opacity", function(d) { return d.dx * ky > 12 ? 1 : 0; });
        
        d3.event.stopPropagation();
    }
    
    function transform(d) {
         return "translate(8," + d.dx * ky / 2 + ")";
    }
});

</script>
</body>
</html>