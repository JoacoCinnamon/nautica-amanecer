<?php
interface IUpdateCascada
{
  /**
   * Método para conectar como una cascada la baja de un cliente o embarcación
   * que si o si llevan a que se tenga que desocupar las distintas amarras en las que tenía sus embarcaciones
   *
   * @return void
   */
  public function updateEnCascada();
}
