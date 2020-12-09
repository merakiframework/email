<?php
declare(strict_types=1);

namespace Meraki\Email;

use Meraki\Email\Exception\DisplayName as DisplayNameException;

// CFWS is not supported by class.
final class DisplayName
{
	/**
	 * atext = ALPHA / DIGIT / "!" / "#" / "$" / "%" / 
	 *		   "&" / "'" / "*" / "+" / "-" / "/" / "=" / 
	 *		   "?" / "^" / "_" / "`" / "{" / "|" / "}" / "~"
	 *
	 * atom  = 1*atext
	 */
	const ATOM_REGEX = "/^[a-zA-Z0-9!#$%&'\*\+\-\/\=\?\^_`{|}~]+$/";

	/**
	 * FWS				=	([*WSP CRLF] 1*WSP)
	 * qtext			=	%d33 / %d35-91 / %d93-126
	 * quoted-pair		=	("\" (VCHAR / WSP))
	 * qcontent			=	qtext / quoted-pair
	 * quoted-string	=	DQUOTE *([FWS] qcontent) [FWS] DQUOTE
	 */
	const QUOTED_STRING_REGEX = '/^"((?:\s|\t)?[\x21\x23-\x5B\x5D-\x7E])+"$/';

	// 1*(atom / quoted-string)
	private $phrase;

	public function __construct(string $phrase)
	{
		// try to match "atom" text
		if (preg_match(self::ATOM_REGEX, $phrase)) {
			$this->phrase = $phrase;
			
		// quote phrase and try matching "quoted-string" text
		} else if (preg_match(self::QUOTED_STRING_REGEX, sprintf('"%s"', $phrase))) {
		    $this->phrase = sprintf('"%s"', $phrase);
		
		// phrase cannot be matched or is empty
		} else {
		    throw DisplayNameException::containsInvalidCharacters();
		}
	}

	public function getPhrase(): string
	{
		return $this->phrase;
	}

	public function isAtomText(): bool
	{
		// If $phrase does not start and end with double-quote, then consider it atom-text
		return $this->phrase[0] !== '"' && $this->phrase[strlen($this->phrase) - 1] !== '"';
	}

	public function equals(self $other): bool
	{
		return strcasecmp($this->phrase, $other->phrase) === 0;
	}

	public function __toString(): string
	{
		return $this->phrase;
	}
}
