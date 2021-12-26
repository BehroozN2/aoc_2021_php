<?php

namespace Questions;

class Day23 extends AbstractQuestion
{
    protected function part1(): string
    {
        /*

        I don't have a code solution yet, solved it manually!

        [Initial state]
        #############
        #...........#
        ###C#A#B#C###
          #D#D#B#A#
          #########

        [Cost: 5]
        #############
        #A..........#
        ###C#.#B#C###
          #D#D#B#A#
          #########

        [Cost: 60]
        #############
        #AB.........#
        ###C#.#.#C###
          #D#D#B#A#
          #########

        [Cost: 50]
        #############
        #AB.B.......#
        ###C#.#.#C###
          #D#D#.#A#
          #########

        [Cost: 500]
        #############
        #AB.B.......#
        ###C#.#.#.###
          #D#D#C#A#
          #########

        [Cost: 3]
        #############
        #AB.B.....A.#
        ###C#.#.#.###
          #D#D#C#.#
          #########

        [Cost: 8000]
        #############
        #AB.B.....A.#
        ###C#.#.#.###
          #D#.#C#D#
          #########

        [Cost: 30]
        #############
        #AB.......A.#
        ###C#.#.#.###
          #D#B#C#D#
          #########

        [Cost: 40]
        #############
        #A........A.#
        ###C#B#.#.###
          #D#B#C#D#
          #########

        [Cost: 600]
        #############
        #A........A.#
        ###.#B#C#.###
          #D#B#C#D#
          #########

        [Cost: 9000]
        #############
        #A........A.#
        ###D#B#C#.###
          #D#B#C#.#
          #########

        [Cost: 9]
        #############
        #A..........#
        ###.#B#C#D###
          #A#B#C#D#
          #########

        [Cost: 3]
        #############
        #...........#
        ###A#B#C#D###
          #A#B#C#D#
          #########

        */

        return (
            5 +
            60 +
            50 +
            500 +
            3 +
            8000 +
            30 +
            40 +
            600 +
            9000 +
            9 +
            3
        );
    }

    protected function part2(): string
    {
        /*

        I don't have a code solution yet, solved it manually!

        [Initial state]
        #############
        #...........#
        ###C#A#B#C###
          #D#C#B#A#
          #D#B#A#C#
          #D#D#B#A#
          #########

        [Cost: 5]
        #############
        #A..........#
        ###C#.#B#C###
          #D#C#B#A#
          #D#B#A#C#
          #D#D#B#A#
          #########

        [Cost: 50]
        #############
        #A.........B#
        ###C#.#.#C###
          #D#C#B#A#
          #D#B#A#C#
          #D#D#B#A#
          #########

        [Cost: 50]
        #############
        #A........BB#
        ###C#.#.#C###
          #D#C#.#A#
          #D#B#A#C#
          #D#D#B#A#
          #########

        [Cost: 8]
        #############
        #AA.......BB#
        ###C#.#.#C###
          #D#C#.#A#
          #D#B#.#C#
          #D#D#B#A#
          #########

        [Cost: 50]
        #############
        #AA.....B.BB#
        ###C#.#.#C###
          #D#C#.#A#
          #D#B#.#C#
          #D#D#.#A#
          #########

        [Cost: 800]
        #############
        #AA.....B.BB#
        ###C#.#.#C###
          #D#.#.#A#
          #D#B#.#C#
          #D#D#C#A#
          #########

        [Cost: 800]
        #############
        #AA.....B.BB#
        ###.#.#.#C###
          #D#.#.#A#
          #D#B#C#C#
          #D#D#C#A#
          #########

        [Cost: 40]
        #############
        #AA...B.B.BB#
        ###.#.#.#C###
          #D#.#.#A#
          #D#.#C#C#
          #D#D#C#A#
          #########

        [Cost: 5000]
        #############
        #AA.D.B.B.BB#
        ###.#.#.#C###
          #D#.#.#A#
          #D#.#C#C#
          #D#.#C#A#
          #########

        [Cost: 50]
        #############
        #AA.D...B.BB#
        ###.#.#.#C###
          #D#.#.#A#
          #D#.#C#C#
          #D#B#C#A#
          #########

        [Cost: 60]
        #############
        #AA.D.....BB#
        ###.#.#.#C###
          #D#.#.#A#
          #D#B#C#C#
          #D#B#C#A#
          #########

        [Cost: 70]
        #############
        #AA.D......B#
        ###.#.#.#C###
          #D#B#.#A#
          #D#B#C#C#
          #D#B#C#A#
          #########

        [Cost: 70]
        #############
        #AA.D.......#
        ###.#B#.#C###
          #D#B#.#A#
          #D#B#C#C#
          #D#B#C#A#
          #########

        [Cost: 500]
        #############
        #AA.D.......#
        ###.#B#.#.###
          #D#B#C#A#
          #D#B#C#C#
          #D#B#C#A#
          #########

        [Cost: 4]
        #############
        #AA.D......A#
        ###.#B#.#.###
          #D#B#C#.#
          #D#B#C#C#
          #D#B#C#A#
          #########

        [Cost: 600]
        #############
        #AA.D......A#
        ###.#B#C#.###
          #D#B#C#.#
          #D#B#C#.#
          #D#B#C#A#
          #########

        [Cost: 5]
        #############
        #AA.D.....AA#
        ###.#B#C#.###
          #D#B#C#.#
          #D#B#C#.#
          #D#B#C#.#
          #########

        [Cost: 9000]
        #############
        #AA.......AA#
        ###.#B#C#.###
          #D#B#C#.#
          #D#B#C#.#
          #D#B#C#D#
          #########

        [Cost: 11000]
        #############
        #AA.......AA#
        ###.#B#C#.###
          #.#B#C#.#
          #D#B#C#D#
          #D#B#C#D#
          #########

        [Cost: 11000]
        #############
        #AA.......AA#
        ###.#B#C#.###
          #.#B#C#D#
          #.#B#C#D#
          #D#B#C#D#
          #########

        [Cost: 11000]
        #############
        #AA.......AA#
        ###.#B#C#D###
          #.#B#C#D#
          #.#B#C#D#
          #.#B#C#D#
          #########

        [Cost: 11]
        #############
        #AA........A#
        ###.#B#C#D###
          #.#B#C#D#
          #.#B#C#D#
          #A#B#C#D#
          #########

        [Cost: 11]
        #############
        #AA.........#
        ###.#B#C#D###
          #.#B#C#D#
          #A#B#C#D#
          #A#B#C#D#
          #########

        [Cost: 3]
        #############
        #A..........#
        ###.#B#C#D###
          #A#B#C#D#
          #A#B#C#D#
          #A#B#C#D#
          #########

        [Cost: 3]
        #############
        #...........#
        ###A#B#C#D###
          #A#B#C#D#
          #A#B#C#D#
          #A#B#C#D#
          #########

        */

        return (
            5 +
            50 +
            50 +
            8 +
            50 +
            800 +
            800 +
            40 +
            5000 +
            50 +
            60 +
            70 +
            70 +
            500 +
            4 +
            600 +
            5 +
            9000 +
            11000 +
            11000 +
            11000 +
            11 +
            11 +
            3 +
            3
        );
    }
}