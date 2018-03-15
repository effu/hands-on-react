<?php
$links = new Files('/', ['html', 'md']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>React! React!</title>
    <link rel="stylesheet" type="text/css" href="bulma-0.6.2/css/bulma.css">
	<script src="https://unpkg.com/react@16/umd/react.development.js"></script>
	<script src="https://unpkg.com/react-dom@16/umd/react-dom.development.js"></script>
	<script src="https://unpkg.com/babel-standalone@6.15.0/babel.min.js"></script>
	<script type="text/babel">

  var links = <?php echo json_encode($links->payload()); ?>;
  var destination = document.querySelector("#main");

  const Test = ({links}) => (
    <div>
      {links.map(link => (
        <div className="link" key={link.date}><a href={link.file} title={link.date}>{link.file}</a></div>
      ))}
    </div>
  );

  ReactDOM.render(
    <div>
    	<h1 className="title is-large">Learning React and Redux</h1>
    	<p>
    		Book source from Safari Books Online:<br />
    		<a href="https://www.safaribooksonline.com/library/view/learning-react-a/9780134843582/ch03.html">
    			Learning React: A Hands-On Guide to Building Web Applications Using React and Redux
    		</a>
    	</p>
    	<h1 className="title is-large">Project Files</h1>

      <Test links={links}/>

    </div>,
    destination
  );
  </script>
</head>
<body>
<section class="section is-medium">
    <div class="container" id="main"></div>
    <div class="container" id="links"></div>
</section>
</body>
</html>

<?php
class Files
{

    protected $dir;
    // protected $extension;
    protected $files = [];

    public function __construct($dir = "/", $extensions = ['html'])
    {
        $this->dir = $dir;
        // $this->extension = $extension;
        foreach ($extensions as $extension) {
            $this->getFileList($extension);
        }
    }

    public function getFileList($extension)
    {
        $arr = [];
        $files = scandir('.' . $this->dir);
        $len = strlen($extension) + 1;
        foreach ($files as $file) {
            if (strpos(substr($file, -$len, $len), '.' . $extension) !== false) {
                $this->files[] = $file;
            }
        }
    }

    public function getDropdown($name = "file", $currentFile)
    {

        $options = '';
        foreach ($this->files as $file) {
            $sel = ($file == $currentFile) ? 'selected' : '';
            $options .= sprintf('<option value="%s" %s>%s - %s</option>', $file, $sel, $file, Date('Y-m-d H:i:s', filemtime('.' . $this->dir . $file)));
        }
        return sprintf('<select name="%s" class="form-control">%s</select>', $name, $options);
    }
    public function getLinks()
    {
        $res = '';
        foreach ($this->files as $file) {
            // $sel = ($file == $currentFile) ? 'selected' : '';
            $res .= sprintf('<p><a href="%s">%s</a> %s</p>', $file, $file, Date('Y-m-d H:i:s', filemtime('.' . $this->dir . $file)));
        }
        return $res;
    }
    public function payload()
    {
        $arr = [];
        foreach ($this->files as $file) {
            // $sel = ($file == $currentFile) ? 'selected' : '';
            $arr[] = ['file' => $file, 'date' => Date('Y-m-d H:i:s', filemtime('.' . $this->dir . $file))];
        }

        return $arr;
    }
}
