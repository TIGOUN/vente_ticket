<?php
if (!function_exists('conditionalAction')) {
    /**
     * Retourne une action basée sur une condition.
     *
     * @param mixed $condition La condition à évaluer.
     * @param string $trueAction L'action à retourner si la condition est vraie.
     * @param string $falseAction L'action à retourner si la condition est fausse.
     * @return string
     */
    function conditionalAction($condition, $trueAction, $falseAction)
    {
        return $condition ? $trueAction : $falseAction;
    }
}