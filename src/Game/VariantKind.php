<?php
	namespace App\Game;

	enum VariantKind: string {
		case Tempered = 'tempered';
		case ArchTempered = 'arch-tempered';
		case Frenzied = 'frenzied';
		case Alpha = 'alpha';
	}