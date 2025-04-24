var nActor = 0;
var nAction = 0;
var nActivity = 0;

var graphLocation; // kalo error kasih = [0]
var graphType = [0];
var graphShape;
var actorName = [];
var activities = [];
var actorLoc = [];
var shape = [];
var tools = [];
var times = [];
var outputs = [];
var notes = [];
var rowHeights = [];
var tdBase = "";
var falseData;
var falseTarget;
var falseX = 0;
var falseY = 0;

function check(i, j) {
	shape = document.getElementById("gShape-" + i + "-" + j).value;
	f = document.getElementById("f-" + i + "-" + j);
	if (shape == '3') {
		f.className = "form-group";
	} else {
		f.className = "form-group hidden";
	}
}

function check2(act, actor) {
	nActivity = act;
	nActor = actor;
	for (i = 1; i <= nActivity; i++) {
		for (j = 1; j <= nActor; j++) {
			shape = document.getElementById("shape-" + i + "-" + j).innerHTML;
			f = document.getElementById("f-" + i + "-" + j);
			if (shape == '3') {
				f.className = "form-group";
			} else {
				f.className = "form-group hidden";
			}
			for (x = 0; x <= 3; x++) {
				option = document.getElementById("option-" + i + "-" + j + "-" + x);
				option.selected = "false";
			}
			option2 = document.getElementById("option-" + i + "-" + j + "-" + shape);
			option2.selected = "true";
		}
	}
}

function getRowHeight(row) {
	var activity = activities[row];
	var tool = tools[row];
	var time = times[row];
	var output = outputs[row];
	var note = notes[row];

	var height = 80;
	var maxHeight = 620;
	var contents = [activity, tool, time, output, note];
	contents.forEach(text => {
		var nRow = text.length / 16;
		var yRow = nRow * 13 + 20;
		if (yRow > maxHeight) yRow = maxHeight;
		if (yRow > height) height = yRow;
	});
	return height;
}

function loadData() {
	console.log("Loading page elements");
	graphShape = createArray(nActivity, nActor);
	graphLocation = createArray(nActivity, nActor);
	falseData = createArray(nActivity, nActor);
	falseTarget = createArray(nActivity, nActor);
	var count;
	for (let a = 0; a < nActor; a++) {
		actorName[a] = document.getElementById('pel-' + a).innerText;
	}
	for (i = 1; i <= nActivity; i++) {
		count = 1;
		for (j = 1; j <= nActor; j++) {
			falseTarget[i - 1][j - 1] = 0;
			shape = document.getElementById("gShape-" + i + "-" + j).value;
			switch (shape) {
				case '1':
					graphShape[i - 1][j - 1] = 'state';
					break;
				case '2':
					graphShape[i - 1][j - 1] = 'process';
					break;
				case '3':
					graphShape[i - 1][j - 1] = 'condition';
					falseData[i - 1][j - 1] = document.getElementById("falseTo-" + i + "-" + j).value;
					convTo2Dim(falseData[i - 1][j - 1]);
					// console.log(falseX);

					falseTarget[falseY][falseX] = 1;
					break;
				default:
					graphShape[i - 1][j - 1] = 0;
					break;
			}
			if (shape != '0' && falseTarget[i - 1][j - 1] == 0) {
				graphLocation[i - 1][j - 1] = count;
				count++;
			} else {
				graphLocation[i - 1][j - 1] = 0;
			}
		}
		activities[i - 1] = document.getElementById("act-" + i).value;
		tools[i - 1] = document.getElementById("tool-" + i).value;
		times[i - 1] = document.getElementById("time-" + i).value;
		outputs[i - 1] = document.getElementById("output-" + i).value;
		notes[i - 1] = document.getElementById("note-" + i).value;

		rowHeights.push(getRowHeight(i - 1));
	}
	// console.log('Graph False Target');
	// console.log(falseTarget);
	// console.log('Graph Location');
	// console.log(graphLocation);
	// console.log('Graph Shape');
	// console.log(graphShape);
	// console.log('Output');
	// console.log(outputs);
	console.log("All components are loaded");
}

function preview() {

	// Display Preview
	document.getElementById('previewBox').style = "";
	document.getElementById('bPreview').innerHTML = "Update Preview";

	// console.log('Box unhidden');


	// Save all data that we need to display
	loadData();

	// Prepare main container
	var totalHeight = 0;
	var mainContainer = document.getElementById('graphContainer');
	mainContainer.innerHTML = '';

	var start = 1;
	var end = 1;
	var page = 0;
	actorLoc = createArray(nActivity + 1, nActor);
	shape = createArray(nActivity + 2, nActor);
	var maxWidth = (nActor + 6) * 120;
	var maxHeight = 820;
	while (end <= nActivity) {
		var height = 150;
		start = end;
		while (true && end <= nActivity) {
			page++;
			var estimatedHeight = height + rowHeights[end - 1];
			if (estimatedHeight >= maxHeight) break;

			height = estimatedHeight;
			end++;
		}
		if (start > 1 && end < nActivity) {
			height += 50;
		}
		totalHeight += height;
		
		// Draw page
		console.log('Start ' + start + ' End ' + (end - 1) + ' Height ' + height);
		var container = document.createElement("div");
		container.style = 'position:relative;overflow:hidden;width:'+maxWidth+'px;height:'+height+'px;border:white dotted 1px;cursor:default;';
		mainContainer.append(container);
		draw(container, start, end - 1);
	}

	// Recalculate box height
	var mainContainerBox = document.getElementById('graphContainerBox');
	mainContainerBox.style = 'height:' + totalHeight + 'px;';
	return true;
}

function draw(container, start, end) {
	// Reset the preview container
	container.innerHTML = "";
	let pageSize = 5;

	// Checks if the browser is supported
	if (!mxClient.isBrowserSupported()) {
		// Displays an error message if the browser is not supported.
		mxUtils.error('Browser is not supported!', 200, false);
	} else {

		// ORTH
		// Enables guides
		mxGraphHandler.prototype.guidesEnabled = true;

		// Alt disables guides
		mxGuide.prototype.isEnabledForEvent = function (evt) {
			return !mxEvent.isAltDown(evt);
		};

		// Enables snapping waypoints to terminals
		mxEdgeHandler.prototype.snapToTerminals = true;

		// Enables orthogonal connect preview in IE
		mxConnectionHandler.prototype.movePreviewAway = false;

		// Disables the built-in context menu
		mxEvent.disableContextMenu(container);

		// Creates the graph inside the given container
		var graph = new mxGraph(container);
		graph.setHtmlLabels(true);
		// Graph configure for Contstraint
		graph.disconnectOnMove = false;
		graph.foldingEnabled = false;
		graph.cellsResizable = false;
		graph.extendParents = false;
		graph.setConnectable(true);
		// Implements perimeter-less connection points as fixed points (computed before the edge style).
		graph.view.updateFixedTerminalPoint = function (edge, terminal, source, constraint) {
			mxGraphView.prototype.updateFixedTerminalPoint.apply(this, arguments);

			var pts = edge.absolutePoints;
			var pt = pts[(source) ? 0 : pts.length - 1];

			if (terminal != null && pt == null && this.getPerimeterFunction(terminal) == null) {
				edge.setAbsoluteTerminalPoint(new mxPoint(this.getRoutingCenterX(terminal),
					this.getRoutingCenterY(terminal)), source)
			}
		};

		graph.isCellEditable = function (cell) {
			return !this.model.isEdge(cell);
		};

		var style = graph.getStylesheet().getDefaultVertexStyle();
		style[mxConstants.STYLE_SHAPE] = mxConstants.SHAPE_RECTANGLE;
		style[mxConstants.STYLE_PERIMETER] = mxPerimeter.RectanglePerimeter;
		style[mxConstants.STYLE_FONTSIZE] = 10;
		style[mxConstants.STYLE_ROUNDED] = false;
		style[mxConstants.STYLE_VERTICAL_ALIGN] = 'middle';
		style[mxConstants.STYLE_MOVEABLE] = 0;
		style[mxConstants.STYLE_RESIZABLE] = 0;
		style[mxConstants.STYLE_EDITABLE] = 0;
		style[mxConstants.STYLE_FONTCOLOR] = 'black';
		style[mxConstants.STYLE_STROKECOLOR] = 'black';
		style[mxConstants.STYLE_SPACING_TOP] = 5;
		style[mxConstants.STYLE_SPACING_LEFT] = 5;
		style[mxConstants.STYLE_SPACING_RIGHT] = 5;
		style[mxConstants.STYLE_SPACING_BOTTOM] = 5;
		style[mxConstants.STYLE_FILLCOLOR] = 'white';
		style[mxConstants.STYLE_WHITE_SPACE] = 'wrap';
		graph.getStylesheet().putCellStyle('process', style);

		style = mxUtils.clone(style);
		style[mxConstants.STYLE_VERTICAL_ALIGN] = 'top';
		style[mxConstants.STYLE_ALIGN] = 'left';
		graph.getStylesheet().putCellStyle('process_text', style);

		style = mxUtils.clone(style);
		style[mxConstants.STYLE_SHAPE] = mxConstants.SHAPE_ELLIPSE;
		delete style[mxConstants.STYLE_STARTSIZE];
		style[mxConstants.STYLE_FONTCOLOR] = 'black';
		style[mxConstants.STYLE_STROKECOLOR] = 'black';
		style[mxConstants.STYLE_LABEL_BACKGROUNDCOLOR] = 'white';
		style[mxConstants.STYLE_FILLCOLOR] = 'white';
		graph.getStylesheet().putCellStyle('state', style);

		style = mxUtils.clone(style);
		style[mxConstants.STYLE_SHAPE] = mxConstants.SHAPE_RHOMBUS;
		style[mxConstants.STYLE_PERIMETER] = mxPerimeter.RhombusPerimeter;
		style[mxConstants.STYLE_VERTICAL_ALIGN] = 'top';
		delete style[mxConstants.STYLE_ROUNDED];
		style[mxConstants.STYLE_SPACING_TOP] = 40;
		style[mxConstants.STYLE_SPACING_RIGHT] = 64;
		graph.getStylesheet().putCellStyle('condition', style);

		style = mxUtils.clone(style);
		style[mxConstants.STYLE_SHAPE] = mxConstants.SHAPE_IMAGE;
		style[mxConstants.STYLE_PERIMETER] = mxPerimeter.RectanglePerimeter;
		style[mxConstants.STYLE_IMAGE] = '../../../src/images/offpage.png';
		style[mxConstants.STYLE_FONTSIZE] = 14;
		style[mxConstants.STYLE_FONTSTYLE] = 1;
		delete style[mxConstants.STYLE_SPACING_RIGHT];
		graph.getStylesheet().putCellStyle('off-page', style);

		style = graph.getStylesheet().getDefaultEdgeStyle();
		style[mxConstants.STYLE_EDGE] = 'orthogonalEdgeStyle';
		style[mxConstants.STYLE_ENDARROW] = mxConstants.ARROW_BLOCK;
		style[mxConstants.STYLE_ROUNDED] = false;
		delete style[mxConstants.STYLE_FILLCOLOR];
		style[mxConstants.STYLE_FONTCOLOR] = 'black';
		style[mxConstants.STYLE_STROKECOLOR] = 'black';
		style[mxConstants.STYLE_LABEL_BACKGROUNDCOLOR] = 'white';
		//style[mxConstants.STYLE_VERTICAL_LABEL_POSITION] = 'ALIGN_BOTTOM';

		style = mxUtils.clone(style);
		style[mxConstants.STYLE_EDGE] = mxEdgeStyle.SideToSide;
		graph.getStylesheet().putCellStyle('side', style);

		// Implements the connect preview
		graph.connectionHandler.createEdgeState = function (me) {
			var edge = graph.createEdge(null, null, null, null, null);

			return new mxCellState(this.graph.view, edge, this.graph.getCellStyle(edge));
		};

		// Gets the default parent for inserting new cells. This
		// is normally the first child of the root (ie. layer 0).
		var parent = graph.getDefaultParent();

		// Adds cells to the model in a single step
		graph.getModel().beginUpdate();
		try {
			var xPointer = 0;
			var yPointer = 0;
			var wBase = 100;
			var wNo = 40;
			var wAct = 120;
			var wActor = wBase * nActor;
			var wMutu = wBase * 3;
			var wNote = 120;
			var wTotal = wNo + wAct + wActor + wMutu + wNote;
			var yHeadTop = 25;
			var yHeadBottom = 55;
			var yHead = yHeadTop + yHeadBottom;
			var yOffPage = 50;

			// Calculate container max Height
			var yTotal = yHead;
			for (let i = start; i <= end; i++) {
				yTotal = yTotal + rowHeights[i-1];
			}
			if (start != 1) {
				yTotal = yTotal + yOffPage;
			}
			if (end != nActivity) {
				yTotal = yTotal + yOffPage;
			}
			var pool = graph.insertVertex(parent, null, '', xPointer, yPointer, wTotal, yTotal, 'strokeColor=none;');
			var fcPool = graph.insertVertex(parent, null, '', xPointer, yPointer, wTotal, yTotal, 'fillOpacity=0;strokeColor=none;');
			var notouch = graph.insertVertex(parent, null, '', xPointer, yPointer, wTotal, yTotal, 'fillOpacity=0;editable=0;movable=0;strokeColor=none;');
			pool.setConnectable(false);

			// Head
			var lane1 = graph.insertVertex(pool, null, '', xPointer, yPointer, wTotal, yHead);
			var no = graph.insertVertex(lane1, null, 'No.', xPointer, yPointer, wNo, yHead);
			xPointer = xPointer + wNo;
			var act = graph.insertVertex(lane1, null, 'Kegiatan', xPointer, yPointer, wAct, yHead);
			xPointer = xPointer + wAct;
			var actor = graph.insertVertex(lane1, null, 'Pelaksana', xPointer, yPointer, wActor, yHead, 'verticalAlign=top');
			var actorList = [0];
			for (var i = 1; i <= nActor; i++) {
				actorList[i] = graph.insertVertex(actor, null, actorName[i - 1], (i - 1) * wBase, yHeadTop, wBase, yHeadBottom);
			}
			xPointer = xPointer + wActor;
			var mutubaku = graph.insertVertex(lane1, null, 'Mutu Baku', xPointer, yPointer, wMutu, yHead, 'verticalAlign=top');
			var syarat = graph.insertVertex(mutubaku, null, 'Kelengkapan', 0, yHeadTop, wBase, yHeadBottom);
			var waktu = graph.insertVertex(mutubaku, null, 'Waktu', wBase, yHeadTop, wBase, yHeadBottom);
			var keluaran = graph.insertVertex(mutubaku, null, 'Output', 2 * wBase, yHeadTop, wBase, yHeadBottom);
			xPointer = xPointer + wMutu;
			var ket = graph.insertVertex(lane1, null, 'Keterangan', xPointer, yPointer, wNote, yHead);

			var yTemp = yPointer + yHead;
			// Body
			//actorLoc = createArray(nActivity + 1, nActor);
			
			// Start Off Page
			if (start != 1) {
				xPointer = 0;
				yPointer = 0;
				var lane = graph.insertVertex(pool, null, '', xPointer, yTemp, wTotal, yOffPage);
				var no = graph.insertVertex(lane, null, '', xPointer, yPointer, wNo, yOffPage);
				xPointer = xPointer + wNo;
				var act = graph.insertVertex(lane, null, '', xPointer, yPointer, wAct, yOffPage, 'process_text');
				xPointer = xPointer + wAct;
				var actor = graph.insertVertex(lane, null, '', xPointer, yPointer, wActor, yOffPage, 'process_text');
				var topRow = [];
				for (var j = 0; j < nActor; j++) {
					topRow[j] = graph.insertVertex(actor, null, '', j * wBase, yPointer, wBase, yOffPage);
				}
				xPointer = xPointer + wActor;
				var mutubaku = graph.insertVertex(lane, null, '', xPointer, yPointer, wMutu, yOffPage, 'process_text');
				var syarat = graph.insertVertex(mutubaku, null, '', 0, yPointer, wBase, yOffPage, 'process_text');
				var waktu = graph.insertVertex(mutubaku, null, '', wBase, yPointer, wBase, yOffPage, 'process_text');
				var keluaran = graph.insertVertex(mutubaku, null, '', 2 * wBase, yPointer, wBase, yOffPage, 'process_text');
				xPointer = xPointer + wMutu;
				var ket = graph.insertVertex(lane, null, '', xPointer, yPointer, wNote, yOffPage, 'process_text');
				yTemp = yTemp + yOffPage;
			}

			// Data Row
			for (var i = start; i <= end; i++) {
				xPointer = 0;
				yPointer = 0;
				var activity = activities[i-1];
				var tool = tools[i - 1];
				var time = times[i - 1];
				var output = outputs[i - 1];
				var note = notes[i - 1];

				var yBaseTemp = rowHeights[i - 1];

				var lane = graph.insertVertex(pool, null, '', xPointer, yTemp, wTotal, yBaseTemp);
				var no = graph.insertVertex(lane, null, i, xPointer, yPointer, wNo, yBaseTemp);
				xPointer = xPointer + wNo;
				var act = graph.insertVertex(lane, null, activity, xPointer, yPointer, wAct, yBaseTemp, 'process_text');
				xPointer = xPointer + wAct;
				var actor = graph.insertVertex(lane, null, '', xPointer, yPointer, wActor, yBaseTemp, 'process_text');
				for (var j = 0; j < nActor; j++) {
					actorLoc[i - 1][j] = graph.insertVertex(actor, null, '', j * wBase, yPointer, wBase, yBaseTemp);
				}
				xPointer = xPointer + wActor;
				var mutubaku = graph.insertVertex(lane, null, '', xPointer, yPointer, wMutu, yBaseTemp, 'process_text');
				var syarat = graph.insertVertex(mutubaku, null, tool, 0, yPointer, wBase, yBaseTemp, 'process_text');
				var waktu = graph.insertVertex(mutubaku, null, time, wBase, yPointer, wBase, yBaseTemp, 'process_text');
				var keluaran = graph.insertVertex(mutubaku, null, output, 2 * wBase, yPointer, wBase, yBaseTemp, 'process_text');
				xPointer = xPointer + wMutu;
				var ket = graph.insertVertex(lane, null, note, xPointer, yPointer, wNote, yBaseTemp, 'process_text');
				yTemp = yTemp + yBaseTemp;
			}

			// Off-Page Row
			if (end != nActivity) {
				xPointer = 0;
				yPointer = 0;
				num = '';
				actL = '';
				toolL = '';
				timeL = '';
				outputL = '';
				noteL = '';
				if (end == nActivity) {
					num = end;
					actL = activities[i - 1];
					toolL = tools[i - 1];
					timeL = times[i - 1];
					outputL = outputs[i - 1];
					noteL = notes[i - 1];
				}
				var lane = graph.insertVertex(pool, null, '', xPointer, yTemp, wTotal, yOffPage);
				var no = graph.insertVertex(lane, null, num, xPointer, yPointer, wNo, yOffPage);
				xPointer = xPointer + wNo;
				var act = graph.insertVertex(lane, null, actL, xPointer, yPointer, wAct, yOffPage, 'process_text');
				xPointer = xPointer + wAct;
				var actor = graph.insertVertex(lane, null, '', xPointer, yPointer, wActor, yOffPage, 'process_text');
				var botRow = [];
				for (var j = 0; j < nActor; j++) {
					botRow[j] = graph.insertVertex(actor, null, '', j * wBase, yPointer, wBase, yOffPage);
				}
				xPointer = xPointer + wActor;
				var mutubaku = graph.insertVertex(lane, null, '', xPointer, yPointer, wMutu, yOffPage, 'process_text');
				var syarat = graph.insertVertex(mutubaku, null, toolL, 0, yPointer, wBase, yOffPage, 'process_text');
				var waktu = graph.insertVertex(mutubaku, null, timeL, wBase, yPointer, wBase, yOffPage, 'process_text');
				var keluaran = graph.insertVertex(mutubaku, null, '', 2 * wBase, yPointer, wBase, yOffPage, 'process_text');
				xPointer = xPointer + wMutu;
				var ket = graph.insertVertex(lane, null, noteL, xPointer, yPointer, wNote, yOffPage, 'process_text');
				yTemp = yTemp + yOffPage;
			}

			// Flowchart
			//var shape = createArray(nActivity, nActor);
			xStart = wNo + wAct;
			yPointer = yHead;
			// Start Off Page
			if (start != 1) {
				for (var z = 0; z < nActor; z++) {
					xPointer = xStart;
					if (graphLocation[start - 2][z] == 1) {
						var top = z;
						xPointer = xPointer + (z * wBase);
						shape[nActivity][z] = graph.insertVertex(fcPool, null, '', xPointer + 25, yPointer + 10, 50, 25, 'off-page');
						// console.log('top=' + top);
						var d = 1;
						var point0 = graph.insertVertex(shape[nActivity][z], null, '', 0, 0.5, d, d,
							'portConstraint=west;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;' +
							'routingCenterX=1;routingCenterY=0;', true);
						point0.geometry.offset = new mxPoint(-d, -(0.5 * d));
						var point1 = graph.insertVertex(shape[nActivity][z], null, '', 1, 0.5, d, d,
							'portConstraint=east;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;' +
							'routingCenterX=0;routingCenterY=0;', true);
						point1.geometry.offset = new mxPoint(-d, -(0.5 * d));
						var point2 = graph.insertVertex(shape[nActivity][z], null, '', 0.5, 0, d, d,
							'portConstraint=north;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;', true);
						point2.geometry.offset = new mxPoint(-(0.5 * d), -d);
						var point3 = graph.insertVertex(shape[nActivity][z], null, '', 0.5, 1, d, d,
							'portConstraint=south;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;', true);
						point3.geometry.offset = new mxPoint(-(0.5 * d), -d);
					} else {
						shape[nActivity][z] = 0;
						continue;
					}
				}
				yPointer = yPointer + yOffPage;
			}
			for (var y = start - 1; y < end; y++) {
				for (var z = 0; z < nActor; z++) {
					xPointer = xStart;
					if (graphShape[y][z] != 0) {
						xPointer = xPointer + (z * wBase);
						var shapeWidth = 50;
						var shapeHeight = 25;
						if (graphShape[y][z] == 'state') {
							shapeWidth = 40;
							shapeHeight = 40;
						}
						
						var wCenter = wBase / 2;
						var yCenter = rowHeights[y] / 2;
						var xPoint = xPointer + (wCenter - shapeWidth / 2);
						var yPoint = yPointer + (yCenter - shapeHeight / 2) + 7;
						shape[y][z] = graph.insertVertex(fcPool, null, '', xPoint, yPoint, shapeWidth, shapeHeight, graphShape[y][z]);

						var d = 1;
						var point0 = graph.insertVertex(shape[y][z], null, '', 0, 0.5, d, d,
							'portConstraint=west;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;' +
							'routingCenterX=1;routingCenterY=0;', true);
						point0.geometry.offset = new mxPoint(-d, -(0.5 * d));
						var point1 = graph.insertVertex(shape[y][z], null, '', 1, 0.5, d, d,
							'portConstraint=east;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;' +
							'routingCenterX=0;routingCenterY=0;', true);
						point1.geometry.offset = new mxPoint(-d, -(0.5 * d));
						var point2 = graph.insertVertex(shape[y][z], null, '', 0.5, 0, d, d,
							'portConstraint=north;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;', true);
						point2.geometry.offset = new mxPoint(-(0.5 * d), -d);
						var point3 = graph.insertVertex(shape[y][z], null, '', 0.5, 1, d, d,
							'portConstraint=south;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;', true);
						point3.geometry.offset = new mxPoint(-(0.5 * d), -d);
						if (graphShape[y][z] == 'condition') {
							var point4 = graph.insertVertex(shape[y][z], null, '', 0.25, 0.25, d, d,
								'portConstraint=west;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;' +
								'routingCenterX=1;routingCenterY=0;', true);
							point4.geometry.offset = new mxPoint(-d, -(0.5 * d));
						} else {
							var point4 = graph.insertVertex(shape[y][z], null, '', 0, 0.5, d, d,
								'portConstraint=west;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;' +
								'routingCenterX=1;routingCenterY=0;', true);
							point4.geometry.offset = new mxPoint(-d, -(0.5 * d));
						}
						if (start != 1 && y == start - 1) {
							graph.insertEdge(fcPool, null, null, shape[nActivity][top].getChildAt(3), shape[y][z].getChildAt(2));
						}
					} else {
						shape[y][z] = 0;
						continue;
					}
				}
				yPointer = yPointer + rowHeights[y];
			}
			if (end != nActivity) {
				for (var z = 0; z < nActor; z++) {
					xPointer = xStart;
					if (graphLocation[end - 1][z] == 1) {
						xPointer = xPointer + (z * wBase);
						shape[nActivity + 1][z] = graph.insertVertex(fcPool, null, '', xPointer + 25, yPointer + 12, 50, 25, 'off-page');
						var d = 1;
						var point0 = graph.insertVertex(shape[nActivity + 1][z], null, '', 0, 0.5, d, d,
							'portConstraint=west;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;' +
							'routingCenterX=1;routingCenterY=0;', true);
						point0.geometry.offset = new mxPoint(-d, -(0.5 * d));
						var point1 = graph.insertVertex(shape[nActivity + 1][z], null, '', 1, 0.5, d, d,
							'portConstraint=east;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;' +
							'routingCenterX=0;routingCenterY=0;', true);
						point1.geometry.offset = new mxPoint(-d, -(0.5 * d));
						var point2 = graph.insertVertex(shape[nActivity + 1][z], null, '', 0.5, 0, d, d,
							'portConstraint=north;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;', true);
						point2.geometry.offset = new mxPoint(-(0.5 * d), -d);
						var point3 = graph.insertVertex(shape[nActivity + 1][z], null, '', 0.5, 1, d, d,
							'portConstraint=south;shape=ellipse;exitPerimeter=0;strokeColor=none;fillColor=none;', true);
						point3.geometry.offset = new mxPoint(-(0.5 * d), -d);
						graph.insertEdge(fcPool, null, null, shape[end - 1][z].getChildAt(3), shape[nActivity + 1][z].getChildAt(2));
						// console.log('created bot shape');
					} else {
						shape[nActivity + 1][z] = 0;
						continue;
					}
				}
				yPointer = yPointer + yOffPage;
			}
			// console.log('Shape');
			// console.log(shape);

			// Connector
			var leftDot = 0;
			var rightDot = 0;
			// console.log('Dot: ' + leftDot + '-' + rightDot);
			for (var k = start - 1; k < end; k++) {
				var sTemp = 0;
				var first = true;
				for (var l = 0; l < nActor; l++) {
					/* Horizontal Line
					if (first && shape[k][l] != 0) {
						sTemp = shape[k][l];
						first = false;
					} else if (!first && shape[k][l] != 0) {
						//graph.insertEdge(fcPool, null, null, sTemp, shape[k][l]);
						graph.insertEdge(fcPool, null, null, sTemp.getChildAt(1), shape[k][l].getChildAt(0));
						sTemp = shape[k][l];
					}
					 */

					// Vertical Line
					if (graphLocation[k][l] == 1) {
						for (var m = 0; m < nActor; m++) {
							if (k + 1 == end) {
								break;
							}
							if (graphLocation[k + 1][m] != 0) {
								if (graphShape[k][l] == 'condition') {
									graph.insertEdge(fcPool, null, 'Ya', shape[k][l].getChildAt(3), shape[k + 1][m].getChildAt(2), 'verticalAlign=bottom;align=right');
								} else {
									graph.insertEdge(fcPool, null, null, shape[k][l].getChildAt(3), shape[k + 1][m].getChildAt(2));
								}

							}
						}
					}

					// Condition Line
					if (graphShape[k][l] == 'condition') {
						convTo2Dim(falseData[k][l]);
						try {
							if (leftDot == 0) {
								graph.insertEdge(fcPool, null, 'Tidak', shape[k][l].getChildAt(0), shape[falseY][falseX].getChildAt(0), 'verticalAlign=bottom;align=left');
								leftDot = 1;
								// console.log('Dot: ' + leftDot + '-' + rightDot);
							} else if (rightDot == 0) {
								graph.insertEdge(fcPool, null, 'Tidak', shape[k][l].getChildAt(1), shape[falseY][falseX].getChildAt(1), 'verticalAlign=top;align=right');
								rightDot = 1;
								// console.log('Dot: ' + leftDot + '-' + rightDot);
							} else {
								graph.insertEdge(fcPool, null, 'Tidak', shape[k][l].getChildAt(0), shape[falseY][falseX].getChildAt(0), 'verticalAlign=bottom;align=left');
								leftDot = 1;
								rightDot = 0;
								// console.log('Reset Dot: ' + leftDot + '-' + rightDot);
							}
						} catch {
							// console.log("Condition in first row");
						}
					}
					/* if (graphShape[k][l] == 'condition') {
						convTo2Dim(falseData[k][l]);
						if (graphShape[falseY][falseX] == 'condition') {
							graph.insertEdge(fcPool, null, 'Tidak', shape[k][l].getChildAt(0), shape[falseY][falseX].getChildAt(3), 'verticalAlign=top;align=right');
						} else {
							graph.insertEdge(fcPool, null, 'Tidak', shape[k][l].getChildAt(0), shape[falseY][falseX].getChildAt(0), 'align=left');
						}
					} */
				}
			}

		} finally {
			// Updates the display
			graph.getModel().endUpdate();
		}
	}
}

function convTo2Dim(x) {
	falseX = (x % nActor) - 1;
	falseY = Math.floor(x / nActor);
	if (falseX == -1) {
		falseX = falseX + nActor;
		falseY = falseY - 1;
	}
}

function createArray(length) {
	var arr = new Array(length || 0),
		i = length;

	if (arguments.length > 1) {
		var args = Array.prototype.slice.call(arguments, 1);
		while (i--) arr[length - 1 - i] = createArray.apply(this, args);
	}

	return arr;
}
