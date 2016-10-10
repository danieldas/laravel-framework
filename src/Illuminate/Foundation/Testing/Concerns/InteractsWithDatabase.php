<?php

namespace Illuminate\Foundation\Testing\Concerns;

use PHPUnit_Framework_Constraint_Not as ReverseConstraint;
use Illuminate\Foundation\Testing\Constraints\HasInDatabase;

trait InteractsWithDatabase
{
    /**
     * Assert that a given where condition exists in the database.
     *
     * @param  string  $table
     * @param  array  $data
     * @param  string  $connection
     * @param  bool  $reverse
     * @return $this
     */
    protected function seeInDatabase($table, array $data, $connection = null, $reverse = false)
    {
        $constraint = new HasInDatabase($data, $this->getConnection($connection));

        if ($reverse) {
            $constraint = new ReverseConstraint($constraint);
        }

        $this->assertThat($table, $constraint);

        return $this;
    }

    /**
     * Assert that a given where condition exists in the database and retrieve the results found.
     *
     * @param  string  $table
     * @param  array  $data
     * @param  string  $connection
     * @return $this
     */
    protected function seeAndGetInDatabase($table, array $data, $connection = null)
    {
        $constraint = new HasInDatabase($data, $this->getConnection($connection));

        $this->assertThat($table, $constraint);

        $results = $constraint->getResults();

        return $results->count() == 1 ? $results->first() : $results;
    }

    /**
     * Assert that a given where condition does not exist in the database.
     *
     * @param  string  $table
     * @param  array  $data
     * @param  string  $connection
     * @return $this
     */
    protected function missingFromDatabase($table, array $data, $connection = null)
    {
        return $this->notSeeInDatabase($table, $data, $connection);
    }

    /**
     * Assert that a given where condition does not exist in the database.
     *
     * @param  string  $table
     * @param  array  $data
     * @param  string  $connection
     * @return $this
     */
    protected function dontSeeInDatabase($table, array $data, $connection = null)
    {
        return $this->notSeeInDatabase($table, $data, $connection);
    }

    /**
     * Assert that a given where condition does not exist in the database.
     *
     * @param  string  $table
     * @param  array  $data
     * @param  string  $connection
     * @return $this
     */
    protected function notSeeInDatabase($table, array $data, $connection = null)
    {
        return $this->seeInDatabase($table, $data, $connection, true);
    }

    /**
     * Seed a given database connection.
     *
     * @param  string  $class
     * @return void
     */
    public function seed($class = 'DatabaseSeeder')
    {
        $this->artisan('db:seed', ['--class' => $class]);
    }

    /**
     * Get the database connection.
     *
     * @param  string|null  $connection
     * @return \Illuminate\Database\Collection
     */
    protected function getConnection($connection = null)
    {
        $database = $this->app->make('db');

        $connection = $connection ?: $database->getDefaultConnection();

        return $database->connection($connection);
    }
}
