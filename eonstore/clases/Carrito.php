<?php
class Cart 
{
    protected $cartId;
	protected $maxProds = 0;
	protected $maxCantidad = 0;
	protected $useCookie = false;
    private $items = [];
    
	public function __construct($opciones = [])
	{
		if (!session_id()) {
			session_start();
        }
        
		if (isset($opciones['maxProds']) && preg_match('/^\d+$/', $opciones['maxProds'])) {
			$this->maxProds = $opciones['maxProds'];
		}

		if (isset($opciones['maxCantidad']) && preg_match('/^\d+$/', $opciones['maxCantidad'])) {
			$this->maxCantidad = $opciones['maxCantidad'];
		}

		if (isset($opciones['useCookie']) && $opciones['useCookie']) {
			$this->useCookie = true;
		}

		$this->cartId = md5((isset($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : 'CarritoSencillo') . '_cart';

		$this->leer();
    }
    
	public function obtenerItems()
	{
		return $this->items;
	}

	public function estaVacio()
	{
		return empty(array_filter($this->items));
	}

	public function getTotalItem()
	{
		$total = 0;

		foreach ($this->items as $items) {
			foreach ($items as $item) {
				++$total;
			}
		}

		return $total;
	}

	public function obtenerCantidadTotal()
	{
		$cantidad = 0;

		foreach ($this->items as $items) {
			foreach ($items as $item) {
				$cantidad += $item['cantidad'];
			}
		}

		return $cantidad;
	}

	public function obtenerTotalAtributo($atributo = 'precio')
	{
		$total = 0;

		foreach ($this->items as $items) {
			foreach ($items as $item) {
				if (isset($item['atributos'][$atributo])) {
					$total += $item['atributos'][$atributo] * $item['cantidad'];
				}
			}
		}

		return $total;
	}

	public function vaciar()
	{
		$this->items = [];
		$this->escribir();
	}

	public function existeItem($id, $atributos = [])
	{
		$atributos = (is_array($atributos)) ? array_filter($atributos) : [$atributos];

		if (isset($this->items[$id])) {
			$hash = md5(json_encode($atributos));
			foreach ($this->items[$id] as $item) {
				if ($item['hash'] == $hash) {
					return true;
				}
			}
		}

		return false;
	}

	public function agregar($id, $cantidad = 1, $atributos = [])
	{
		$cantidad = (preg_match('/^\d+$/', $cantidad)) ? $cantidad : 1;
		$atributos = (is_array($atributos)) ? array_filter($atributos) : [$atributos];
		$hash = md5(json_encode($atributos));

		if (count($this->items) >= $this->maxProds && $this->maxProds != 0) {
			return false;
		}

		if (isset($this->items[$id])) {
			foreach ($this->items[$id] as $index => $item) {
				if ($item['hash'] == $hash) {
					$this->items[$id][$index]['cantidad'] += $cantidad;
					$this->items[$id][$index]['cantidad'] = ($this->maxCantidad < $this->items[$id][$index]['cantidad'] && $this->maxCantidad != 0) ? $this->maxCantidad : $this->items[$id][$index]['cantidad'];

					$this->escribir();

					return true;
				}
			}
		}

		$this->items[$id][] = [
			'id'         => $id,
			'cantidad'   => ($cantidad > $this->maxCantidad && $this->maxCantidad != 0) ? $this->maxCantidad : $cantidad,
			'hash'       => $hash,
			'atributos' => $atributos,
		];

		$this->escribir();

		return true;
	}

	public function actualizar($id, $cantidad = 1, $atributos = [])
	{
		$cantidad = (preg_match('/^\d+$/', $cantidad)) ? $cantidad : 1;

		if ($cantidad == 0) {
			$this->remover($id, $atributos);

			return true;
		}

		if (isset($this->items[$id])) {
			$hash = md5(json_encode(array_filter($atributos)));

			foreach ($this->items[$id] as $index => $item) {
				if ($item['hash'] == $hash) {
					$this->items[$id][$index]['cantidad'] = $cantidad;
					$this->items[$id][$index]['cantidad'] = ($this->maxCantidad < $this->items[$id][$index]['cantidad'] && $this->maxCantidad != 0) ? $this->maxCantidad : $this->items[$id][$index]['cantidad'];

					$this->escribir();

					return true;
				}
			}
		}

		return false;
	}

	public function remover($id, $atributos = [])
	{
		if (!isset($this->items[$id])) {
			return false;
		}

		if (empty($atributos)) {
			unset($this->items[$id]);

			$this->escribir();

			return true;
		}
		$hash = md5(json_encode(array_filter($atributos)));

		foreach ($this->items[$id] as $index => $item) {
			if ($item['hash'] == $hash) {
				unset($this->items[$id][$index]);

				$this->escribir();

				return true;
			}
		}

		return false;
	}

	public function destroy()
	{
		$this->items = [];

		if ($this->useCookie) {
			setcookie($this->cartId, '', -1);
		} else {
			unset($_SESSION[$this->cartId]);
		}
	}

	private function leer()
	{
		$this->items = ($this->useCookie) ? json_decode((isset($_COOKIE[$this->cartId])) ? $_COOKIE[$this->cartId] : '[]', true) : json_decode((isset($_SESSION[$this->cartId])) ? $_SESSION[$this->cartId] : '[]', true);
	}

	private function escribir()
	{
		if ($this->useCookie) {
			setcookie($this->cartId, json_encode(array_filter($this->items)), time() + 604800);
		} else {
			$_SESSION[$this->cartId] = json_encode(array_filter($this->items));
		}
	}
}