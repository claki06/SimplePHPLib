<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            color: white;
            font-family: 'Courier New', Courier, monospace;
        }

        p{
            font-size: 1.3rem;
            color: #EF5350;
        }

        body {
            background-color: #27374D;
        }

        .logo-container{
            padding: 10px;
            display: flex;
            justify-content: center;
            box-shadow: 0 0 50px 20px #2d3f59;
        }

        .content-wrapper{
            margin-top: 50px;
            display: flex;
        }

        .details-container{
            padding: 20px;
        }

        .message-container{
            padding: 20px;
        }

        .placeholder{
            padding: 10px;
            margin: 20px 0;
            width: 100%;
            background-color: #3b4f5e ;
            border-radius: 20px;
            border: 2px solid #31424e;

        }
    </style>

</head>
<body>
    
    <div class="logo-container">

        <svg
            width="70px"
            height="86px"
            viewBox="0 0 47.273884 68.633339"
            version="1.1"
            id="svg5"
            inkscape:version="1.1.2 (0a00cf5339, 2022-02-04)"
            sodipodi:docname="s-logo.svg"
            xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
            xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
            xmlns="http://www.w3.org/2000/svg"
            xmlns:svg="http://www.w3.org/2000/svg">
            <sodipodi:namedview
                id="namedview7"
                pagecolor="#ffffff"
                bordercolor="#666666"
                borderopacity="1.0"
                inkscape:pageshadow="2"
                inkscape:pageopacity="0.0"
                inkscape:pagecheckerboard="0"
                inkscape:document-units="mm"
                showgrid="false"
                fit-margin-top="0"
                fit-margin-left="0"
                fit-margin-right="0"
                fit-margin-bottom="0"
                inkscape:zoom="0.64052329"
                inkscape:cx="108.50503"
                inkscape:cy="188.12743"
                inkscape:window-width="1850"
                inkscape:window-height="1016"
                inkscape:window-x="1350"
                inkscape:window-y="27"
                inkscape:window-maximized="1"
                inkscape:current-layer="layer1" />
            <defs
                id="defs2" />
            <g
                inkscape:label="Layer 1"
                inkscape:groupmode="layer"
                id="layer1"
                transform="translate(-83.591023,-91.208768)">
                <text
                xml:space="preserve"
                style="font-style:normal;font-weight:normal;font-size:87.8397px;line-height:1.25;font-family:sans-serif;fill:#ffcc00;fill-opacity:1;stroke:#000000;stroke-width:2.19599;stroke-opacity:1"
                x="78.898804"
                y="157.50029"
                id="text1706"><tspan
                    sodipodi:role="line"
                    id="tspan1704"
                    style="fill:#ffcc00;stroke:#000000;stroke-width:2.19599;stroke-opacity:1"
                    x="78.898804"
                    y="157.50029">S</tspan></text>
            </g>
        </svg>

    </div>

    <div class="details-container">
        <h1>Error Type:</h1>

        <div class="placeholder">
            <p><?php echo $errorType ?></p>
        </div>

        <h1>File:</h1>

        <div class="placeholder">
            <p><?php echo $error["file"] ?></p>
        </div>

        <h1>Line:</h1>

        <div class="placeholder">
            <p><?php echo $error["line"] ?></p>
        </div>

        <h1>Error Message:</h1>

        <div class="placeholder">
            <p><?php echo $error["message"] ?></p>
        </div>

    </div>


    


</body>
</html>