<?php

namespace NotificationChannels\Asana;

use DateTime;
use Torann\LaravelAsana\Facade\Asana;

class AsanaMessage
{
    /** @var string */
    protected $name;

    /** @var string|null */
    protected $notes;

    /** @var string|null */
    protected $dueOn;

    /** @var string|null */
    protected $assignee;

    /** @var string|null */
    protected $workspace;

    /** @var string|null */
    protected $projects;

    /**
     * @param string $name
     *
     * @return static
     */
    public static function create($name)
    {
        return new static($name);
    }

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
        $this->notes = '';
    }

    /**
     * Set the task name.
     *
     * @param $name
     *
     * @return $this
     */
    public function name($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set the task notes.
     *
     * @param $notes
     *
     * @return $this
     */
    public function notes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Set the task assignee (id or email address).
     *
     * @param string $assignee
     *
     * @return $this
     */
    public function assignee($assignee)
    {
        $this->assignee = $assignee;

        return $this;
    }

    /**
     * Set the task workspace (id or email address).
     *
     * @param string $workspace
     *
     * @return $this
     */
    public function workspace($workspace)
    {
        $this->workspace = $workspace;

        return $this;
    }

    /**
     * Set the task projects (id or email address).
     *
     * @param string|array $projects
     *
     * @return $this
     */
    public function projects($projects)
    {
        $this->projects = $projects;

        return $this;
    }

    /**
     * Set the task dueOn date.
     *
     * @param string|DateTime $dueOn
     *
     * @return $this
     */
    public function dueOn($dueOn)
    {
        if (!$dueOn instanceof DateTime) {
            $dueOn = new DateTime($dueOn);
        }

        $this->dueOn = $dueOn->format('Y-m-d');

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'name'      => $this->name,
            'notes'     => $this->notes,
            'assignee'  => $this->assignee,
            'workspace' => $this->workspace ? $this->workspace : asana()->defaultWorkspaceId,
            'projects'  => $this->projects ? $this->projects : asana()->defaultProjectId,
            'due_on'    => $this->dueOn,
        ];
    }
}
