<?php
class Local extends CI_Model
{


    public function __construct()
    {
        parent::__construct();
    }


    /**
     * @brief Obtiene ciertos elementos de un registro de una tabla.
     * @details Produce: SELECT $select FROM $tabla WHERE $condicion1 = '$condicion2';
     *
     * @param $tabla especifica la tabla que va a obtener
     * @param $select los campos que va a seleccionar
     * @param $condicion1 el campo de la tabla, por lo general es la llave primaria o un campo unico
     * @param $condicion2 el dato para comparar con el campo de la tabla
     * @return $query->result() retorna el resultado en un objeto
     * @return $data retorna un error si la consulta no tuvo exito
     */
    public function getElementWhere($tabla, $select, $condicion1, $condicion2) {
        $this->db->protect_identifiers($tabla);
        $this->db->select($select);
        $this->db->where($condicion1, $condicion2);
        $query = $this->db->get($tabla);
        if(!$query){
            $data['error'] = $this->db->_error_message();
            return $data;
        }else{
            return $query->result();
        }
    }

    /**
     * @brief Agrega o inserta datos la base de datos local
     *
     * @param $tabla especifica la tabla que va a insertar los $datos
     * @param $datos contiene un arreglo de campos-valores
     * @return $data retorna un error si la consulta no tuvo exito
     * @return $query true la consulta fue exitosa
     */
    function add($tabla, $datos){
        $this->db->protect_identifiers($tabla);
        $query = $this->db->insert($tabla, $datos);
        if(!$query){
            $data['error'] = $this->db->_error_message();
            return $data;
        }else{
            return $query;
        }
    }

    /**
     * @brief Obtiene todos los registros de $tabla
     * @details Produce: SELECT * FROM $tabla;
     *
     * @param $tabla especifica la tabla que va a obtener
     * @return $query->result() retorna el resultado en una objeto
     * @return $data retorna un error si la consulta no tuvo exito
     */
    function get_register($tabla){
        $this->db->protect_identifiers($tabla);
        $query = $this->db->get($tabla);
        if(!$query){
            $data['error'] = $this->db->_error_message();
            return $data;
        }else{
            return $query->result();
        }
    }

    /**
     * @brief Obtiene un solo registro de la $tabla
     * @details Priduce: SELECT * FROM $tabla WHERE $condicion1 = '$condicion2';
     *
     * @param $tabla especifica la tabla que va a obtener
     * @param $condicion1 el campo de la tabla, por lo general es la llave primaria o un campo unico
     * @param $condicion2 el dato para comparar con el campo de la tabla
     * @return $query->result() retorna el resultado en una objeto
     * @return $data retorna un error si la consulta no tuvo exito
     */
    function get_register2($tabla, $condicion1, $condicion2){
        $this->db->protect_identifiers($tabla);
        $this->db->where($condicion1, $condicion2);
        $query = $this->db->get($tabla);
        if(!$query){
            $data['error'] = $this->db->_error_message();
            return $data;
        }else{
            return $query->result();
        }
    }

    /**
     * @brief Actualiza la informacion de la base de datos local
     *
     * @details UPDATE $tabla SET $camposSet WHERE $condicion1 = '$condicion2';
     *
     * @param $tabla especifica la tabla que va a actualizar los $datos
     * @param $camposSet un arreglo de los campos y valores que se van a actualizar
     * @param $condicion1 el campo de la tabla, por lo general es la llave primaria o un campo unico
     * @param $condicion2 el dato para comparar con el campo de la tabla
     * @return $query true la consulta fue exitosa
     * @return $data retorna un error si la consulta no tuvo exito
     */
    function update($tabla, $camposSet, $condicion1, $condicion2){
        $this->db->protect_identifiers($tabla);
        $this->db->where($condicion1, $condicion2);
        $query =  $this->db->update($tabla, $camposSet);
        if(!$query){
            $data['error'] = $this->db->_error_message();
            return $data;
        }else{
            return $query;
        }
    }

    /**
     * @brief Eliminar la informacion de la base de datos local
     * @details Produce: DELETE FROM $tabla WHERE $condicion;
     *
     * @param $tabla especifica la tabla que va a eliminar los datos
     * @param $condicion contiene un arreglo de campo-valor
     * @return $query true la consulta fue exitosa
     * @return $data retorna un error si la consulta no tuvo exito
     */
    function delete($tabla, $condicion) {
        $this->db->protect_identifiers($tabla);
        $query = $this->db->delete($tabla, $condicion);
        if(!$query){
            $data['error'] = $this->db->_error_message();
            return $data;
        }else{
            return $query;
        }
    }

    function get_register_sql($sql) {
        $this->db->protect_identifiers($sql);
        $query = $this->db->query($sql);
        if(!$query){
            $data['error'] = $this->db->_error_message();
            return $data;
        }else{
            return $query->result();
        }
    }

    /**
     * @brief Obtiene registros de varias tablas
     * @details Produce: SELECT * FROM $tabla JOIN $tabla2 ON condicion1 JOIN $tabla3 ON condicion2;
     *
     * @param $tabla especifica la tabla que va a obtener
     * @param $tabla2 especifica la tabla que va a obtener los registros cruzados
     * @param $tabla3 especifica la tabla que va a obtener los registros cruzados
     * @param $condicion1 compara llaver foráneas
     * @param $condicion2 compara llaver foráneas
     * @return $query->result() retorna el resultado en una objeto
     * @return $data retorna un error si la consulta no tuvo exito
     */
    function get_register_join($tabla1, $condicion1, $condicion2) {
        $this->db->protect_identifiers($tabla1);
        $this->db->select('*');
        $this->db->from($tabla1);
        $this->db->join($condicion1);
        $this->db->join($condicion2);
        $query = $this->db->get();
        if(!$query){
            $data['error'] = $this->db->_error_message();
            return $data;
        }else{
            return $query->result();
        }
    }
}
?>