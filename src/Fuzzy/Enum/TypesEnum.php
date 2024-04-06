<?php

namespace Eightyfour\Fuzzy\Enum;

enum TypesEnum: string
{
    case Fuzzy = 'fuzzy';
    case Neuron = 'neuron';
    case Network = 'network';
    case NeuralNetwork = 'neural_network';

    public static function getLabel(self $item): string
    {
        return match ($item) {
            self::Neuron => self::Neuron->value,
            self::Network => self::Network->value,
            self::NeuralNetwork => self::NeuralNetwork->value,
            self::Fuzzy => self::Fuzzy->value
        };
    }
}
