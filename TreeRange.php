<?php
echo "<prE>";
$T = [];
$T[0] = 2;
$T[1] = 0;
$T[2] = 2;
$T[3] = 2;
$T[4] = 1;
$T[5] = 0;

echo "<prE>";
$T = [];
$T[0] = 2;
$T[1] = 2;
$T[2] = 2;


class DijkstraN
{
    /** @var integer[][] The graph, where $graph[node1][node2]=cost */
    protected $graph;

    /** @var int[] Distances from the source node to each other node */
    protected $distance;

    /** @var string[][] The previous node(s) in the path to the current node */
    protected $previous;

    /** @var int[] Nodes which have yet to be processed */
    protected $queue;

    /**
     * @param integer[][] $graph
     */
    public function __construct($graph)
    {
        $this->graph = $graph;
    }

    /**
     * Process the next (i.e. closest) entry in the queue.
     *
     * @param string[] $exclude A list of nodes to exclude - for calculating next-shortest paths.
     *
     * @return void
     */
    protected function processNextNodeInQueue(array $exclude)
    {
        // Process the closest vertex
        $closest = array_search(min($this->queue), $this->queue);
        if (!empty($this->graph[$closest]) && !in_array($closest, $exclude)) {
            foreach ($this->graph[$closest] as $neighbor => $cost) {
                if (isset($this->distance[$neighbor])) {
                    if ($this->distance[$closest] + $cost < $this->distance[$neighbor]) {
                        // A shorter path was found
                        $this->distance[$neighbor] = $this->distance[$closest] + $cost;
                        $this->previous[$neighbor] = array($closest);
                        $this->queue[$neighbor] = $this->distance[$neighbor];
                    } elseif ($this->distance[$closest] + $cost === $this->distance[$neighbor]) {
                        // An equally short path was found
                        $this->previous[$neighbor][] = $closest;
                        $this->queue[$neighbor] = $this->distance[$neighbor];
                    }
                }
            }
        }
        unset($this->queue[$closest]);
    }

    /**
     * Extract all the paths from $source to $target as arrays of nodes.
     *
     * @param string $target The starting node (working backwards)
     *
     * @return string[][] One or more shortest paths, each represented by a list of nodes
     */
    protected function extractPaths($target)
    {
        $paths = array(array($target));

        for ($key = 0; isset($paths[$key]); ++$key) {
            $path = $paths[$key];

            if (!empty($this->previous[$path[0]])) {
                foreach ($this->previous[$path[0]] as $previous) {
                    $copy = $path;
                    array_unshift($copy, $previous);
                    $paths[] = $copy;
                }
                unset($paths[$key]);
            }
        }

        return array_values($paths);
    }

    /**
     * Calculate the shortest path through a a graph, from $source to $target.
     *
     * @param string   $source  The starting node
     * @param string   $target  The ending node
     * @param string[] $exclude A list of nodes to exclude - for calculating next-shortest paths.
     *
     * @return string[][] Zero or more shortest paths, each represented by a list of nodes
     */
    public function shortestPath($source, $target, array $exclude = array())
    {
        // The shortest distance to all nodes starts with infinity...
        $this->distance = array_fill_keys(array_keys($this->graph), INF);
        // ...except the start node
        $this->distance[$source] = 0;

        // The previously visited nodes
        $this->previous = array_fill_keys(array_keys($this->graph), array());

        // Process all nodes in order
        $this->queue = array($source => 0);
        while (!empty($this->queue)) {
            $this->processNextNodeInQueue($exclude);
        }

        if ($source === $target) {
            // A null path
            return array(array($source));
        } elseif (empty($this->previous[$target])) {
            // No path between $source and $target
            return array();
        } else {
            // One or more paths were found between $source and $target
            return $this->extractPaths($target);
        }
    }
}




function solution($T)
{
    $graph = buildGraph($T);
   
    $sets = [];
    foreach ($T as $node_key => $node) {

        if ($node_key == 0) {
            // continue;
        }

        foreach ($T as $visiting_key => $visiting) {

            if ($visiting_key <  $node_key) {

                continue;
            }

            if (correct_path($node_key, $visiting_key, $graph)) {

                $sets[] = [$node_key, $visiting_key];
            }
        }
        if ($node_key ==  1) {
            //   breaK;

        }
    }
   // print_r($sets);
    return count($sets);
}

function findpath($source, $target, $graph, $tru_origin, $tru_destination)
{

    static $cache_paths;
   // echo "\n findpath args $source, $target \n";
    if (isset($cache_paths[$source . "_" . $target])) {
      //  echo "\n findpath used cache \n";
        return $cache_paths[$source . "_" . $target];
    }



    $g = new DijkstraN($graph);

    $s = $g->shortestPath($source, $target);  // 3:D->E->C

    $cache_paths[$source . "_" . $target] = $s[0];

    return $s[0] ;
}



function correct_path($origin, $destination, $graph)
{

    //echo "\n args $origin, $destination \n";
    $day = $origin;
    $found = true;
    while ($day < $destination) {

        $path = findpath($day, ($day + 1), $graph, $origin, $destination);
       // print_r($path);
        if ($path === false) {
            $found = false;
            $brk_while = true;
            break;
        } else {
            $prev_vertex = null;

            foreach ($path as $vertex) {


                $brk_while = false;

                if (!($origin <=  $vertex &&  $vertex <= $destination)) {

                    $brk_while = true;
                    $found = false;
                    break;
                }
            }

            if ($brk_while) {
                break;
            }
        }

        $day++;
    }


    return $found;
}



function buildGraph($T)
{
    $nodes = [];

    foreach ($T as $node_key => $node) {

        if ($node_key == $node) {
            continue;
        }
        $nodes[$node_key][$node] =  1;

        $nodes[$node][$node_key] =  1;
    }

    return $nodes;
}
echo solution($T);
