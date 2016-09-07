<?php

namespace QuickDashboard\Application\Controllers;

class DashboardController {
	public function __construct(ApplicationAbstract $app) 
	{

	}

	public function showSalesOverview() 
	{

	}

	private function countActiveCustomers(\DateTime $start, \DateTime $end) : array
	{
		$result = [];

		$q = new Query();
		$q->select('row_id', 'Kundennummer')
			->from('Kunde_Belegkopf_Archiv', 'Kunden')
			->where('BELEGDATUM', '>=' $start->format('Y-m-d H:m:i'))
			->where('BELEGDATUM', '<=' $start->format('Y-m-d H:m:i'), 'AND')
			->where('KUNDENID', '=' 'ROW_ID', 'AND')
			->unique('KUNDENID')
			->count();

		$customers = $q->execute();

		$result['SD'] => [
			'total' => 0.0,
			'eu' => 0.0,
			'asia' => 0.0,
			'america' => 0.0,
			'africa' => 0.0,
			'oceania' => 0.0,
			'domestic' => 0.0,
			'export' => 0.0,
			'developed' => 0.0,
			'undeveloped' => 0.0,
			'country' => [],
		];

		foreach($customers as $customer) {

		}

		$q->setDatabase();

		$result['GDF'] = $q->execute();

		return $result;
	}

	private function countInvoices(\DateTime $start, \DateTime $end) : array
	{
		$result = [];

		$q = new Query();
		$q->select('row_id')
			->from('Kunde_Belegkopf_Archiv')
			->where('BELEGDATUM', '>=' $start->format('Y-m-d H:m:i'))
			->where('BELEGDATUM', '<=' $start->format('Y-m-d H:m:i'), 'AND')
			->and(
				(new Where())
				->where('Belegtyp', '=' 'VR0')
				->where('Belegtyp', '=' 'VRS')
				->where('Belegtyp', '=' 'VRT')
				->where('Belegtyp', '=' 'VW0')
				->where('Belegtyp', '=' 'VG0')
			)
			->unique('row_id')
			->count();

		$result['SD'] = $q->execute();

		$q->setDatabase();

		$result['GDF'] = $q->execute();

		return $result;
	}

	private function sumNetSales(\DateTime $start, \DateTime $end) : array
	{
		$result = [];

		$q = new Query();
		$q->select('WARENWERTNETTO', 'Vertreter', 'Belegtyp', 'LAENDERKUERZEL')
			->from('Kunde_Belegkopf_Archiv', 'Kunden')
			->where('BELEGDATUM', '>=' $start->format('Y-m-d H:m:i'))
			->where('BELEGDATUM', '<=' $start->format('Y-m-d H:m:i'), 'AND')
			->where('KUNDENID', '=' 'ROW_ID', 'AND')
			->and(
				(new Where())
				->where('Belegtyp', '=' 'VR0')
				->where('Belegtyp', '=' 'VRS')
				->where('Belegtyp', '=' 'VRT')
				->where('Belegtyp', '=' 'VW0')
				->where('Belegtyp', '=' 'VG0')
			);

		$invoices = $q->execute();

		$result['SD'] => [
			'total' => 0.0,
			'eu' => 0.0,
			'asia' => 0.0,
			'america' => 0.0,
			'africa' => 0.0,
			'oceania' => 0.0,
			'domestic' => 0.0,
			'export' => 0.0,
			'developed' => 0.0,
			'undeveloped' => 0.0,
			'country' => [],
		];

		foreach($invoices as $invoice) {

		}

		return $result;
	}
}