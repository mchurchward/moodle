<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Provides support for the conversion of moodle1 backup to the moodle2 format
 *
 * @package    block_participants
 * @copyright  2012 Mike Churchward (mike@remote-learner.net)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Block Participants conversion handler
 */
class moodle1_block_participants_handler extends moodle1_block_handler {

    /**
     * Declare the paths in moodle.xml we are able to convert
     *
     * The method returns list of {@link convert_path} instances. For each path returned,
     * at least one of on_xxx_start(), process_xxx() and on_xxx_end() methods must be
     * defined. The method process_xxx() is not executed if the associated path element is
     * empty (i.e. it contains none elements or sub-paths only).
     *
     * Note that the path /MOODLE_BACKUP/COURSE/BLOCKS/BLOCK/PARTICIPANT does not
     * actually exist in the file. The last element with the module name was
     * appended by the moodle1_converter class.
     *
     * @return array of {@link convert_path} instances
     */
    public function get_paths() {
        return array(
            new convert_path(
            	'participants', '/MOODLE_BACKUP/COURSE/BLOCKS/BLOCK/PARTICIPANTS',
                array(
                    'renamefields' => array(
                        'name' => 'blockname'
                    ),
                	'newfields' => array(
                        'version' => '',
                        'showinsubcontexts' => 0,
                        'subpagepattern' => '$@NULL@S',
                        'defaultregion' => 'side-pre',
                        'defaultweight' => 2
                    )
                )
            ),
        );
    }

	/**
     * This is executed every time we have one /MOODLE_BACKUP/COURSE/BLOCKS/BLOCK/PARTICIPANTS
     * data available
     */
    public function process_participants($data) {
        $instanceid = $data['id'];
        $contextid = 0;
        $parentcontextid = 0;

        // Start writing block.xml.
        $this->open_xml_writer("blocks/part_{$this->moduleid}/quiz.xml");
        $this->xmlwriter->begin_tag('activity', array('id' => $instanceid,
                'moduleid' => $this->moduleid, 'modulename' => 'quiz',
                'contextid' => $contextid));
    }
}
