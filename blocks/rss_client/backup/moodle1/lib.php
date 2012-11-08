<?php

/**
 * Provides support for the conversion of moodle1 backup to the moodle2 format
 *
 * @package    block_rss_client
 * @copyright  2012 Paul Nicholls
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Block conversion handler for rss_client
 */
class moodle1_block_rss_client_handler extends moodle1_block_handler {
    /** @var int instanceid */
    protected $instanceid = null;

    /** @var int contextid */
    protected $contextid = null;

    public function get_paths() {
        $paths = parent::get_paths();
        $paths[] = new convert_path('rss_client_feeds', "/MOODLE_BACKUP/COURSE/BLOCKS/BLOCK/RSS_CLIENT/FEEDS");
        $paths[] = new convert_path('rss_client_feed', "/MOODLE_BACKUP/COURSE/BLOCKS/BLOCK/RSS_CLIENT/FEEDS/FEED");
        return $paths;
    }

    public function process_block(array $data) {
        $this->instanceid = $data['id'];
		$this->contextid = $this->converter->get_contextid(CONTEXT_BLOCK, $data['id']);
		return parent::process_block($data);
    }

    /**
     * This is executed when the parser reaches the <FEEDS> opening element
     */
    public function on_rss_client_feeds_start() {
		$this->open_xml_writer("course/blocks/{$this->pluginname}_{$this->instanceid}/rss_client.xml");
        $this->xmlwriter->begin_tag('block', array('id' => $this->instanceid, 'contextid' => $this->contextid, 'blockname' => 'rss_client'));
        $this->xmlwriter->begin_tag('rss_client', array('id' => $this->instanceid));
        $this->xmlwriter->begin_tag('feeds');
    }

    /**
     * This is executed when the parser reaches the closing </FEEDS> element
     */
    public function on_rss_client_feeds_end() {
        $this->xmlwriter->end_tag('feeds');
        $this->xmlwriter->end_tag('rss_client');
        $this->xmlwriter->end_tag('block');
        $this->close_xml_writer();
    }

    public function process_rss_client_feeds(array $data) {
        return $data;
	}

    public function process_rss_client_feed(array $data) {
        $this->xmlwriter->begin_tag('feed', array('id' => $data['id']));
        foreach ($data as $field => $value) {
            if ($field <> 'id') {
                $this->xmlwriter->full_tag($field, $value);
            }
        }
        $this->xmlwriter->end_tag('feed');
        return $data;
	}
}