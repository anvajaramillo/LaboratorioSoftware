<?php
class Local extends CI_Model
{


    public function __construct()
    {
        parent::__construct();
    }

    public function select_login($username,$password){
        $this->db->select('*');
        $this->db->from('Usuarios');
        $this->db->where('usuario_usu',$username);
        $query=$this->db->get();
        $data=array();
        if($query->num_rows() == 1){
            $tmp = $query->row_array();
            $data['is_logued_in'] = true;
            $data['id_usu']=$tmp['id_usu'];
            $data['perfil'] = $tmp['tipo_usu'];
            $data['sede'] = $tmp['cod_sede_usu'];
            return $data;
        }else{
            $this->session->set_flashdata('usuario_incorrecto','El usuario no se encuentra registrado o ha ingresado datos incorrectos, vuelva a intentarlo');
            redirect(base_url().'index.php/Login','refresh');
        }

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
     * @brief Obtiene un solo registro de la $tabla
     * @details Priduce: SELECT * FROM $tabla WHERE $condicion1 = '$condicion2' AND $condicion3 = '$condicion4';
     *
     * @param $tabla especifica la tabla que va a obtener
     * @param $condicion1 el campo de la tabla, por lo general es la llave primaria o un campo unico
     * @param $condicion2 el dato para comparar con el campo de la tabla
     * @return $query->result() retorna el resultado en una objeto
     * @return $data retorna un error si la consulta no tuvo exito
     */
    function get_register3($tabla, $condicion1, $condicion2, $condicion3, $condicion4){
        $this->db->protect_identifiers($tabla);
        $this->db->where($condicion1, $condicion2);
        $this->db->where($condicion3, $condicion4);
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
     * @brief Actualiza la informacion de la base de datos local
     *
     * @details UPDATE $tabla SET $camposSet WHERE $condicion1 = '$condicion2' AND $condicion3 = '$condicion4';
     *
     * @param $tabla especifica la tabla que va a actualizar los $datos
     * @param $camposSet un arreglo de los campos y valores que se van a actualizar
     * @param $condicion1 el campo de la tabla, por lo general es la llave primaria o un campo unico
     * @param $condicion2 el dato para comparar con el campo de la tabla
     * @return $query true la consulta fue exitosa
     * @return $data retorna un error si la consulta no tuvo exito
     */
    function update2($tabla, $camposSet, $condicion1, $condicion2, $condicion3, $condicion4){
        $this->db->protect_identifiers($tabla);
        $this->db->where($condicion1, $condicion2);
        $this->db->where($condicion3, $condicion4);
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
     * @details Produce: SELECT * FROM $tabla1 JOIN $tabla2 ON condicion1;
     *
     * @param $tabla1 especifica la tabla que va a obtener
     * @param $tabla2 especifica la tabla que va a obtener los registros cruzados
     * @param $condicion1 compara llaves foráneas
     * @return $query->result() retorna el resultado en una objeto
     * @return $data retorna un error si la consulta no tuvo exito
     */
    function get_register_join2($tabla1, $tabla2, $condicion1) {
        $this->db->protect_identifiers($tabla1);
        $this->db->protect_identifiers($tabla2);
        $this->db->select('*');
        $this->db->from($tabla1);
        $this->db->join($tabla2, $condicion1);
        $query = $this->db->get();
        if(!$query){
            $data['error'] = $this->db->_error_message();
            return $data;
        }else{
            return $query->result();
        }
    }

    /**
     * @brief Obtiene registros de varias tablas
     * @details Produce: SELECT * FROM $tabla1 JOIN $tabla2 ON condicion1 WHERE $item1 = $item2;
     *
     * @param $tabla1 especifica la tabla que va a obtener
     * @param $tabla2 especifica la tabla que va a obtener los registros cruzados
     * @param $condicion1 compara llaves foráneas
     * @return $query->result() retorna el resultado en una objeto
     * @return $data retorna un error si la consulta no tuvo exito
     */
    function get_register_join2_where($tabla1, $tabla2, $condicion1,$item1,$item2) {
        $this->db->protect_identifiers($tabla1);
        $this->db->protect_identifiers($tabla2);
        $this->db->select('*');
        $this->db->from($tabla1);
        $this->db->join($tabla2, $condicion1);
        $this->db->where($item1, $item2);
        $query = $this->db->get();
        if(!$query){
            $data['error'] = $this->db->_error_message();
            return $data;
        }else{
            return $query->result();
        }
    }

    /**
     * @brief Obtiene registros de varias tablas
     * @details Produce: SELECT * FROM $tabla1 JOIN $tabla2 ON condicion1 WHERE $item1 = $item2 AND $item3 = $item4;
     *
     * @param $tabla1 especifica la tabla que va a obtener
     * @param $tabla2 especifica la tabla que va a obtener los registros cruzados
     * @param $condicion1 compara llaves foráneas
     * @return $query->result() retorna el resultado en una objeto
     * @return $data retorna un error si la consulta no tuvo exito
     */
    function get_register_join2_where_and($tabla1, $tabla2, $condicion1,$item1,$item2,$item3,$item4) {
        $this->db->protect_identifiers($tabla1);
        $this->db->protect_identifiers($tabla2);
        $this->db->select('*');
        $this->db->from($tabla1);
        $this->db->join($tabla2, $condicion1);
        $this->db->where($item1, $item2);
        $this->db->where($item3, $item4);
        $query = $this->db->get();
        if(!$query){
            $data['error'] = $this->db->_error_message();
            return $data;
        }else{
            return $query->result();
        }
    }

    /**
     * @brief Obtiene registros de varias tablas
     * @details Produce: SELECT * FROM $tabla1 JOIN $tabla2 ON condicion1 JOIN $tabla3 ON condicion2;
     *
     * @param $tabla1 especifica la tabla que va a obtener
     * @param $tabla2 especifica la tabla que va a obtener los registros cruzados
     * @param $tabla3 especifica la tabla que va a obtener los registros cruzados
     * @param $condicion1 compara llaves foráneas
     * @param $condicion2 compara llaves foráneas
     * @return $query->result() retorna el resultado en una objeto
     * @return $data retorna un error si la consulta no tuvo exito
     */
    function get_register_join3($tabla1, $tabla2, $condicion1, $tabla3,  $condicion2) {
        $this->db->protect_identifiers($tabla1);
        $this->db->protect_identifiers($tabla2);
        $this->db->protect_identifiers($tabla3);
        $this->db->select('*');
        $this->db->from($tabla1);
        $this->db->join($tabla2, $condicion1);
        $this->db->join($tabla3, $condicion2);
        $query = $this->db->get();
        if(!$query){
            $data['error'] = $this->db->_error_message();
            return $data;
        }else{
            return $query->result();
        }
    }

    /**
     * @brief Obtiene registros de varias tablas
     * @details Produce: SELECT * FROM $tabla1 JOIN $tabla2 ON condicion1 JOIN $tabla3 ON condicion2 GROUP BY $column;
     *
     * @param $tabla1 especifica la tabla que va a obtener
     * @param $tabla2 especifica la tabla que va a obtener los registros cruzados
     * @param $tabla3 especifica la tabla que va a obtener los registros cruzados
     * @param $condicion1 compara llaves foráneas
     * @param $condicion2 compara llaves foráneas
     * @return $query->result() retorna el resultado en una objeto
     * @return $data retorna un error si la consulta no tuvo exito
     */
    function get_register_join3_group_by($tabla1, $tabla2, $condicion1, $tabla3,  $condicion2, $column) {
        $this->db->protect_identifiers($tabla1);
        $this->db->protect_identifiers($tabla2);
        $this->db->protect_identifiers($tabla3);
        $this->db->select('*');
        $this->db->from($tabla1);
        $this->db->join($tabla2, $condicion1);
        $this->db->join($tabla3, $condicion2);
        $this->db->group_by($column);
        $query = $this->db->get();
        if(!$query){
            $data['error'] = $this->db->_error_message();
            return $data;
        }else{
            return $query->result();
        }
    }

    /**
     * @brief Obtiene registros de varias tablas
     * @details Produce: SELECT * FROM $tabla1 JOIN $tabla2 ON condicion1 JOIN $tabla3 ON condicion2 WHERE $item1 = $item2;
     *
     * @param $tabla1 especifica la tabla que va a obtener
     * @param $tabla2 especifica la tabla que va a obtener los registros cruzados
     * @param $tabla3 especifica la tabla que va a obtener los registros cruzados
     * @param $condicion1 compara llaves foráneas
     * @param $condicion2 compara llaves foráneas
     * @return $query->result() retorna el resultado en una objeto
     * @return $data retorna un error si la consulta no tuvo exito
     */
    function get_register_join3_where($tabla1, $tabla2, $condicion1, $tabla3,  $condicion2, $item1, $item2) {
        $this->db->protect_identifiers($tabla1);
        $this->db->protect_identifiers($tabla2);
        $this->db->protect_identifiers($tabla3);
        $this->db->select('*');
        $this->db->from($tabla1);
        $this->db->join($tabla2, $condicion1);
        $this->db->join($tabla3, $condicion2);
        $this->db->where($item1, $item2);
        $query = $this->db->get();
        if(!$query){
            $data['error'] = $this->db->_error_message();
            return $data;
        }else{
            return $query->result();
        }
    }

    /**
     * @brief Obtiene registros de varias tablas
     * @details Produce: SELECT * FROM $tabla1 JOIN $tabla2 ON condicion1 JOIN $tabla3 ON condicion2 WHERE $item1 = $item2 GROUP BY $column;
     *
     * @param $tabla1 especifica la tabla que va a obtener
     * @param $tabla2 especifica la tabla que va a obtener los registros cruzados
     * @param $tabla3 especifica la tabla que va a obtener los registros cruzados
     * @param $condicion1 compara llaves foráneas
     * @param $condicion2 compara llaves foráneas
     * @return $query->result() retorna el resultado en una objeto
     * @return $data retorna un error si la consulta no tuvo exito
     */
    function get_register_join3_where_group_by($tabla1, $tabla2, $condicion1, $tabla3,  $condicion2, $item1, $item2, $column) {
        $this->db->protect_identifiers($tabla1);
        $this->db->protect_identifiers($tabla2);
        $this->db->protect_identifiers($tabla3);
        $this->db->select('*');
        $this->db->from($tabla1);
        $this->db->join($tabla2, $condicion1);
        $this->db->join($tabla3, $condicion2);
        $this->db->where($item1, $item2);
        $this->db->group_by($column);
        $query = $this->db->get();
        if(!$query){
            $data['error'] = $this->db->_error_message();
            return $data;
        }else{
            return $query->result();
        }
    }

    /**
     * @brief Obtiene registros de varias tablas
     * @details Produce: SELECT * FROM $tabla1 JOIN $tabla2 ON condicion1 JOIN $tabla3 ON condicion2 WHERE $item1 = $item2 ORDER BY $column $form;
     *
     * @param $tabla1 especifica la tabla que va a obtener
     * @param $tabla2 especifica la tabla que va a obtener los registros cruzados
     * @param $tabla3 especifica la tabla que va a obtener los registros cruzados
     * @param $condicion1 compara llaves foráneas
     * @param $condicion2 compara llaves foráneas
     * @return $query->result() retorna el resultado en una objeto
     * @return $data retorna un error si la consulta no tuvo exito
     */
    function get_register_join3_where_order_by($tabla1, $tabla2, $condicion1, $tabla3,  $condicion2, $item1, $item2, $column, $form) {
        $this->db->protect_identifiers($tabla1);
        $this->db->protect_identifiers($tabla2);
        $this->db->protect_identifiers($tabla3);
        $this->db->select('*');
        $this->db->from($tabla1);
        $this->db->join($tabla2, $condicion1);
        $this->db->join($tabla3, $condicion2);
        $this->db->where($item1, $item2);
        $this->db->order_by($column, $form);
        $query = $this->db->get();
        if(!$query){
            $data['error'] = $this->db->_error_message();
            return $data;
        }else{
            return $query->result();
        }
    }

    /**
     * @brief Obtiene registros de varias tablas
     * @details Produce: SELECT * FROM $tabla1 JOIN $tabla2 ON condicion1 JOIN $tabla3 ON condicion2 WHERE $item1 = $item2 AND $item3 = $item4;
     *
     * @param $tabla1 especifica la tabla que va a obtener
     * @param $tabla2 especifica la tabla que va a obtener los registros cruzados
     * @param $tabla3 especifica la tabla que va a obtener los registros cruzados
     * @param $condicion1 compara llaves foráneas
     * @param $condicion2 compara llaves foráneas
     * @return $query->result() retorna el resultado en una objeto
     * @return $data retorna un error si la consulta no tuvo exito
     */
    function get_register_join3_where_and($tabla1, $tabla2, $condicion1, $tabla3,  $condicion2, $item1, $item2, $item3, $item4) {
        $this->db->protect_identifiers($tabla1);
        $this->db->protect_identifiers($tabla2);
        $this->db->protect_identifiers($tabla3);
        $this->db->select('*');
        $this->db->from($tabla1);
        $this->db->join($tabla2, $condicion1);
        $this->db->join($tabla3, $condicion2);
        $this->db->where($item1, $item2);
        $this->db->where($item3, $item4);
        $query = $this->db->get();
        if(!$query){
            $data['error'] = $this->db->_error_message();
            return $data;
        }else{
            return $query->result();
        }
    }

    /**
     * @brief Obtiene registros de varias tablas
     * @details Produce: SELECT * FROM $tabla1 JOIN $tabla2 ON condicion1 JOIN $tabla3 ON condicion2 WHERE $item1 = $item2 AND $item2 = $item4 ORDER BY $column $form;
     *
     * @param $tabla1 especifica la tabla que va a obtener
     * @param $tabla2 especifica la tabla que va a obtener los registros cruzados
     * @param $tabla3 especifica la tabla que va a obtener los registros cruzados
     * @param $condicion1 compara llaves foráneas
     * @param $condicion2 compara llaves foráneas
     * @return $query->result() retorna el resultado en una objeto
     * @return $data retorna un error si la consulta no tuvo exito
     */
    function get_register_join3_where_and_order_by($tabla1, $tabla2, $condicion1, $tabla3,  $condicion2, $item1, $item2, $item3, $item4, $column, $form) {
        $this->db->protect_identifiers($tabla1);
        $this->db->protect_identifiers($tabla2);
        $this->db->protect_identifiers($tabla3);
        $this->db->select('*');
        $this->db->from($tabla1);
        $this->db->join($tabla2, $condicion1);
        $this->db->join($tabla3, $condicion2);
        $this->db->where($item1, $item2);
        $this->db->where($item3, $item4);
        $this->db->order_by($column, $form);
        $query = $this->db->get();
        if(!$query){
            $data['error'] = $this->db->_error_message();
            return $data;
        }else{
            return $query->result();
        }
    }

    /**
     * @brief Obtiene registros de varias tablas
     * @details Produce: SELECT $select FROM $tabla1 JOIN $tabla2 ON condicion1 JOIN $tabla3 ON condicion2;
     *
     * @param $select los campos que va a seleccionar
     * @param $tabla1 especifica la tabla que va a obtener
     * @param $tabla2 especifica la tabla que va a obtener los registros cruzados
     * @param $tabla3 especifica la tabla que va a obtener los registros cruzados
     * @param $condicion1 compara llaves foráneas
     * @param $condicion2 compara llaves foráneas
     * @return $query->result() retorna el resultado en una objeto
     * @return $data retorna un error si la consulta no tuvo exito
     */
    function get_register_join3_select($select, $tabla1, $tabla2, $condicion1, $tabla3,  $condicion2) {
        $this->db->protect_identifiers($tabla1);
        $this->db->protect_identifiers($tabla2);
        $this->db->protect_identifiers($tabla3);
        $this->db->select($select);
        $this->db->from($tabla1);
        $this->db->join($tabla2, $condicion1);
        $this->db->join($tabla3, $condicion2);
        $query = $this->db->get();
        if(!$query){
            $data['error'] = $this->db->_error_message();
            return $data;
        }else{
            return $query->result();
        }
    }

    /**
     * @brief Obtiene registros de varias tablas
     * @details Produce: SELECT $select FROM $tabla1 JOIN $tabla2 ON condicion1 JOIN $tabla3 ON condicion2;
     *
     * @param $select los campos que va a seleccionar
     * @param $tabla1 especifica la tabla que va a obtener
     * @param $tabla2 especifica la tabla que va a obtener los registros cruzados
     * @param $tabla3 especifica la tabla que va a obtener los registros cruzados
     * @param $condicion1 compara llaves foráneas
     * @param $condicion2 compara llaves foráneas
     * @param $where condicional array con dos parametros
     * @return $query->result() retorna el resultado en una objeto
     * @return $data retorna un error si la consulta no tuvo exito
     */
    function get_register_join3_select_where($select, $tabla1, $tabla2, $condicion1, $tabla3,  $condicion2, $where) {
        $this->db->protect_identifiers($tabla1);
        $this->db->protect_identifiers($tabla2);
        $this->db->protect_identifiers($tabla3);
        $this->db->select($select);
        $this->db->from($tabla1);
        $this->db->join($tabla2, $condicion1);
        $this->db->join($tabla3, $condicion2);
        $this->db->where($where);
        $query = $this->db->get();
        if(!$query){
            $data['error'] = $this->db->_error_message();
            return $data;
        }else{
            return $query->result();
        }
    }

    /**
     * @brief Obtiene registros de varias tablas
     * @details Produce: SELECT $select FROM $tabla1 JOIN $tabla2 ON condicion1 JOIN $tabla3 ON condicion2 JOIN $tabla4 ON condicion3;
     *
     * @param $select los campos que va a seleccionar
     * @param $tabla1 especifica la tabla que va a obtener
     * @param $tabla2 especifica la tabla que va a obtener los registros cruzados
     * @param $tabla3 especifica la tabla que va a obtener los registros cruzados
     * @param $condicion1 compara llaves foráneas
     * @param $condicion2 compara llaves foráneas
     * @return $query->result() retorna el resultado en una objeto
     * @return $data retorna un error si la consulta no tuvo exito
     */
    function get_register_join4_select($select, $tabla1, $tabla2, $condicion1, $tabla3,  $condicion2, $tabla4, $condicion3) {
        $this->db->protect_identifiers($tabla1);
        $this->db->protect_identifiers($tabla2);
        $this->db->protect_identifiers($tabla3);
        $this->db->protect_identifiers($tabla4);
        $this->db->select($select);
        $this->db->from($tabla1);
        $this->db->join($tabla2, $condicion1);
        $this->db->join($tabla3, $condicion2);
        $this->db->join($tabla4, $condicion3);
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